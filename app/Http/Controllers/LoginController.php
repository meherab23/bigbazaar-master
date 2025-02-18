<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    

    public function login(Request $request) {
        $email = $request->input('email');
        $encryptedPassword = $request->input('password');
        $iv = base64_decode($request->input('iv'));
    
        $key = '12345678901234567890123456789012'; // Must be the same key used in encryption
    
        // Decode the encrypted password from Base64
        $encryptedPassword = base64_decode($encryptedPassword);
    
        // Decrypt the password
        $decryptedPassword = openssl_decrypt(
            $encryptedPassword,
            'aes-256-cbc',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
    
        if ($decryptedPassword === false) {
            return response()->json(['message' => 'Decryption failed'], 401);
        }
    
        // Retrieve the user
        $user = User::where('email', $email)->first();
    
        // Check if user exists and password is correct
        if ($user && Hash::check($decryptedPassword, $user->password)) {
            return response()->json(['message' => 'Login successful']);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

}
