<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

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
    public function initiatePayment(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'departure-date' => 'required',
            'return-date' => 'required',
            'cars' => 'required',
            'total-bill' => 'required|numeric',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
       
        $endpointUrl = 'https://api.easypaisa.com.pk/payment/initiate';
    
       
        $requestBody = [
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'amount' => $request->input('total-bill'),
            'currency' => 'PKR',
            
        ];
    
        $clientId = '60333e97-b741-41ec-8d09-f5d36e941af4';
        $clientSecret = 'J6aB0fC8yH2sW6cX2jD5hQ2lS1yR0oS7yG6pC1sF8vJ5qA5yF5';
    
        
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $clientId . ':' . $clientSecret,
        ];
        $body = json_encode($requestBody);
    
       
        $client = new Client();
    
        
        $response = $client->post($endpointUrl, [
            'headers' => $headers,
            'body' => $body,
        ]);
    
       
        if ($response->getStatusCode() !== 200) {
            return response()->json(['error' => 'Payment failed. Error: ' . $response->getBody()], 500);
        }
    
        
        $responseData = json_decode($response->getBody(), true);
    
      
        if ($responseData['status'] !== 'SUCCESS') {
            return response()->json(['error' => 'Payment failed. Error: ' . $responseData['message']], 500);
        }
    
        
        $booking = Booking::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'departure_date' => $request->input('departure-date'),
            'return_date' => $request->input('return-date'),
            'car' => $request->input('cars'),
            'total_bill' => $request->input('total-bill'),
        ]);
    
        return response()->json(['message' => 'Payment initiated and booking created successfully', 'booking' => $booking]);
    }
    
}


// $clientId = '60333e97-b741-41ec-8d09-f5d36e941af4';
// $clientSecret = 'J6aB0fC8yH2sW6cX2jD5hQ2lS1yR0oS7yG6pC1sF8vJ5qA5yF5';