<?php

namespace Tests\Unit;

use App\Services\IpLocation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IpLocationTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLV()
    {
        $l = IpLocation::getInstanceFromIp('83.99.128.0');
        $code = $l->get('countryCode');
        $this->assertEquals($code, 'LV');
    }

    public function testUS()
    {
        $l = IpLocation::getInstanceFromIp('142.93.181.178');
        $code = $l->get(IpLocation::COUNTRY_CODE);
        $country = $l->get(IpLocation::COUNTRY);
        $this->assertEquals($code, 'US');
        $this->assertEquals($country, 'United States');
    }

    public function testWrongIp() {
        $code = IpLocation::getInstanceFromIp('asd')->get(IpLocation::COUNTRY_CODE);
        $this->assertEquals($code, env('DEFAULT_COUNTRY', 'US'));
    }
}
