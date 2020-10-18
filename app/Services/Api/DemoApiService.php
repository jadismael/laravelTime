<?php


namespace App\Services\Api;


use App\Exceptions\ApiException;
use App\Models\User;
use App\Models\UserPaymentData;
use App\Models\UserPaymentInformation;
use App\Services\GuzzleService;

class DemoApiService
{
    const URL = "https://37f32cl571.execute-api.eu-central-1.amazonaws.com/default/wunderfleet-recruiting-backend-dev-save-payment-data";

    public function sendPaymentInfo(GuzzleService $guzzleService, User $user)
    {

        $dataToSend = [
            "customerId" => $user->id,
            "iban" => $user->paymentInformation->iban,
            "owner" => $user->paymentInformation->owner

        ];
        $response = $guzzleService->post(self::URL, $dataToSend);
        if ($guzzleService->isSuccessful()) {
            $arrayData = json_decode($response->getBody());
            $userPaymentData = UserPaymentData::create(["payment_data_id" => $arrayData->paymentDataId]);
            if ($userPaymentData) {
                return redirect()->route('home');
            }
        }

        throw new ApiException();

    }
}