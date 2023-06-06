<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\Price;
use Stripe\Checkout\Session;

class CarController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'carTitle' => 'required|string',
            'subTitle' => 'required|string',
            'price' => 'required|numeric',
            'doors' => 'required|integer',
            'passengers' => 'required|integer',
            'luggage' => 'required|string',
            'transmission' => 'required|string',
            'airConditioning' => 'required|string',
            'picture' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $car = new Car($validator->validated());

        if ($request->hasFile('picture')) {
            $picture = $request->file('picture');
            $picturePath = $picture->store('car_pictures', 'public');
            $car->picture = $picturePath;
        }

        $car->save();

        return response()->json(['message' => 'Your car has been added successfully'], 200);
    }

    public function index()
    {
        $cars = Car::get();

        return response()->json($cars);
    }

    public function destroy($id)
    {
        $car = Car::find($id);
        $car->delete();
        return response()->json(['message' => 'Car deleted successfully']);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'carTitle' => 'required',
            'subTitle' => 'required',
            'price' => 'required',
            'doors' => 'required',
            'passengers' => 'required',
            'luggage' => 'required',
            'transmission' => 'required',
            'airConditioning' => 'required',

        ]);

        $car = Car::findOrFail($id);

        $car->carTitle = $validatedData['carTitle'];
        $car->subTitle = $validatedData['subTitle'];
        $car->price = $validatedData['price'];
        $car->doors = $validatedData['doors'];
        $car->passengers = $validatedData['passengers'];
        $car->luggage = $validatedData['luggage'];
        $car->transmission = $validatedData['transmission'];
        $car->airConditioning = $validatedData['airConditioning'];

        if ($request->hasFile('picture')) {
            $picture = $request->file('picture');
            $picturePath = $picture->store('car_pictures', 'public');
            $car->picture = $picturePath;
        }

        $car->save();

        return response()->json(['message' => 'Car updated successfully'], 200);
    }

    public function getCars($id)
    {
        $car = Car::findOrFail($id);
        return response()->json([
            'carTitle' => $car->carTitle,
            'subTitle' => $car->subTitle,
            'price' => $car->price,
            'doors' => $car->doors,
            'passengers' => $car->passengers,
            'luggage' => $car->luggage,
            'transmission' => $car->transmission,
            'airConditioning' => $car->airConditioning,
            'picture' => $car->picture
        ]);
    }

    public function listofbookings()
    {
        $bookings = Booking::get();

        return response()->json($bookings);
    }
    public function destroyBookings($id)
    {
        $bookings = Booking::find($id);
        $bookings->delete();
        return response()->json(['message' => 'Cab Booking deleted successfully']);
    }
    public function updateBookings(Request $request, $id)
    {

        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'departure-date' => 'required',
            'return-date' => 'required',
            'cars' => 'required',
            'total-bill' => 'required|numeric',

        ]);

        $booking = Booking::findOrFail($id);

        $booking->name = $validatedData['name'];
        $booking->phone = $validatedData['phone'];
        $booking->email = $validatedData['email'];
        $booking->departure_date = $validatedData['departure-date'];
        $booking->return_date = $validatedData['return-date'];
        $booking->car = $validatedData['cars'];
        $booking->total_bill = $validatedData['total-bill'];

        $booking->save();

        return response()->json(['message' => 'Booking updated successfully'], 200);
    }

    public function getBookings($id)
    {
        $booking = Booking::findOrFail($id);
        return response()->json([
            'name' => $booking->name,
            'phone' => $booking->phone,
            'email' => $booking->email,
            'departure_date' => $booking->departure_date,
            'return_date' => $booking->return_date,
            'car' => $booking->car,
            'total_bill' => $booking->total_bill,

        ]);
    }

    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'departure-date' => 'required',
            'return-date' => 'required',
            'cars' => 'required',
            'totalBill' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $booking = Booking::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'departure_date' => $request->input('departure-date'),
            'return_date' => $request->input('return-date'),
            'car' => $request->input('cars'),
            'total_bill' => $request->input('totalBill'),
        ]);


        $provider = new PayPalClient([]);

        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        $order = $provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => 'USD',
                        'value' => $request->totalBill
                    ]
                ]
            ],
            'application_context' => [
                'cancel_url' => route('payment.cancel'),
                'return_url' => route('payment.success', ['bookingId' => $booking->id])
            ]
        ]);

        return response()->json(['redirect_url' => $order['links'][1]['href']]);
    }

    public function paymentSuccess(Request $request)
    {
        $bookingId = $request->input('bookingId');

        $booking = Booking::find($bookingId);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        $booking->payment_status = 'completed';
        $booking->save();

        $frontendUrl = rtrim(env('FRONTEND_URL'));

        $redirectUrl = $frontendUrl . '?success=true';

        return redirect()->away($redirectUrl);
    }



    public function paymentCancel()
    {
        $frontendUrl = rtrim(env('FRONTEND_URL'));



        $redirectUrl = $frontendUrl . '?success=false';

        return redirect()->away($redirectUrl);
    }


    public function stripePaymentt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'departureDateTime' => 'required',
            'returnDateTime' => 'required',
            'car' => 'required',
            'totalBill' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $booking = Booking::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'departure_date' => $request->input('departureDateTime'),
            'return_date' => $request->input('returnDateTime'),
            'car' => $request->input('car'),
            'total_bill' => $request->input('totalBill'),
        ]);


        $totalBill = $request->input('totalBill');
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $product = Product::create([
            'name' => $request->input('car')
        ]);

        $price = Price::create([
            'product' => $product->id,
            'unit_amount' => $totalBill * 100,
            'currency' => 'usd',
        ]);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price' => $price->id,
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('payment.success', ['bookingId' => $booking->id]),
            'cancel_url' => route('payment.cancel')
        ]);

        return response()->json(['sessionId' => $session->id], 200);
    }
    public function stripePaymentSuccess(Request $request)
    {
        $bookingId = $request->input('bookingId');

        $booking = Booking::find($bookingId);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        $booking->payment_status = 'completed';
        $booking->save();

        $frontendUrl = rtrim(env('FRONTEND_URL'));



        $redirectUrl = $frontendUrl . '?success=true';

        return redirect()->away($redirectUrl);
    }
    public function stripePaymentCancel()
    {
        $frontendUrl = rtrim(env('FRONTEND_URL'));



        $redirectUrl = $frontendUrl . '?success=false';

        return redirect()->away($redirectUrl);
    }
}
