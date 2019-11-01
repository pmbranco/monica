<?php

namespace Tests\Unit\Services\Contact\Address;

use App\Models\Account\Account;
use App\Models\Contact\Address;
use App\Models\Contact\Contact;
use App\Services\Contact\Address\CreateAddress;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateAddressTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_stores_an_address()
    {
        $contact = factory(Contact::class)->create([]);

        $request = [
            'account_id' => $contact->account_id,
            'contact_id' => $contact->id,
            'name' => 'work address',
            'street' => '199 Lafayette Street',
            'city' => 'New York City',
            'province' => '',
            'postal_code' => '',
            'country' => 'USA',
            'latitude' => '',
            'longitude' => '',
        ];

        $address = app(CreateAddress::class)->execute($request);

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'account_id' => $contact->account_id,
            'name' => 'work address',
        ]);

        $this->assertEquals(
            '199 Lafayette Street',
            $address->place->street
        );

        $this->assertInstanceOf(
            Address::class,
            $address
        );
    }

    public function test_it_fails_if_wrong_parameters_are_given()
    {
        $account = factory(Account::class)->create([]);

        $request = [
            'name' => '199 Lafayette Street',
        ];

        $this->expectException(ValidationException::class);
        app(CreateAddress::class)->execute($request);
    }

    public function test_it_throws_an_exception_if_contact_is_not_linked_to_account()
    {
        $account = factory(Account::class)->create();
        $contact = factory(Contact::class)->create();

        $request = [
            'account_id' => $account->id,
            'contact_id' => $contact->id,
            'name' => 'work address',
            'street' => '199 Lafayette Street',
            'city' => 'New York City',
            'province' => '',
            'postal_code' => '',
            'country' => 'USA',
            'latitude' => '',
            'longitude' => '',
        ];

        $this->expectException(ModelNotFoundException::class);
        app(CreateAddress::class)->execute($request);
    }
}
