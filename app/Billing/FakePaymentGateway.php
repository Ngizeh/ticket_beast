<?php

namespace App\Billing;


class FakePaymentGateway implements PaymentGateway
{
    private $charges;

    private $beforeFirstChargeCallback;

    public function __construct()
    {
        $this->charges = collect();
    }

    public function getValidToken(): string
    {
        return "valid_token";
    }

    public function charge($amount, $token)
    {
        if($this->beforeFirstChargeCallback !== null){
            $callback = $this->beforeFirstChargeCallback;
            $this->beforeFirstChargeCallback = null;
            $callback($this);
        }

        if($token !== $this->getValidToken()){
            throw new PaymentGatewayException;
        }
        $this->charges[] = $amount;
    }

    public function totalCharges()
    {
        return $this->charges->sum();
    }

    public function beforeFirstCharge($callback)
    {
        $this->beforeFirstChargeCallback = $callback;
    }

}
