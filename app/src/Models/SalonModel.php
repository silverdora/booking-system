<?php

namespace App\Models;

class SalonModel
{
    public int $id;
    public string $name;
    public string $type;
    public string $description;
    public string $phone;
    public string $email;
    public string $city;
    public string $address;

    /**
     * @param int $id
     * @param string $name
     * @param string $type
     * @param string $description
     * @param string $phone
     * @param string $email
     * @param string $city
     * @param string $address
     */
    public function __construct(int $id, string $name, string $type, string $description, string $phone, string $email, string $city, string $address)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->phone = $phone;
        $this->email = $email;
        $this->city = $city;
        $this->address = $address;
    }

}