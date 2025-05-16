<?php

use App\Models\Appointment;
use App\Models\HealthcareProfessional;
use App\Models\User;
use Database\Seeders\HealthcareProfessionalSeeder;
use Laravel\Sanctum\Sanctum;

test('book an appointment', function () {

    $this->seed(HealthcareProfessionalSeeder::class);

    $professional = HealthcareProfessional::first();

    $user = Sanctum::actingAs(User::factory()->create());

    $response = $this->actingAs($user)->postJson('/api/appointments', [
        'healthcare_professional_id' => $professional->id,
        'appointment_start_time' => now()->addDays(2),
        'appointment_end_time' => now()->addDays(2)->addHour(),
    ]);

    $response
        ->assertStatus(201)
        ->assertJson([
            'message' => 'Appointment booked successfully.',
        ]);
});

test('cancel an appointment', function () {

    $this->seed(HealthcareProfessionalSeeder::class);

    $professional = HealthcareProfessional::first();

    $user = Sanctum::actingAs(User::factory()->create());

    $appointment = Appointment::factory()->create([
        'user_id' => $user->id,
        'healthcare_professional_id' => $professional->id,
        'appointment_start_time' => now()->addDays(2),
        'appointment_end_time' => now()->addDays(2)->addHour(),
    ]);

    $response = $this->actingAs($user)->patchJson("/api/appointments/{$appointment->id}/cancel");

    $response->assertStatus(200);

    expect($appointment->fresh()->status)->toBe(Appointment::CANCELLED);

});

test('complete an appointment', function () {

    $this->seed(HealthcareProfessionalSeeder::class);

    $professional = HealthcareProfessional::first();

    $user = Sanctum::actingAs(User::factory()->create());

    $appointment = Appointment::factory()->create([
        'user_id' => $user->id,
        'healthcare_professional_id' => $professional->id,
        'appointment_start_time' => now()->addDays(2),
        'appointment_end_time' => now()->addDays(2)->addHour(),
    ]);

    $response = $this->actingAs($user)->patchJson("/api/appointments/{$appointment->id}/complete");

    $response->assertStatus(200);

    expect($appointment->fresh()->status)->toBe(Appointment::COMPLETED);

});

test('get list of my appointments', function () {

    $this->seed(HealthcareProfessionalSeeder::class);

    $user = Sanctum::actingAs(User::factory()->create());

    $professionals = HealthcareProfessional::all();

    $appointment = Appointment::factory()->count(10)->create([
        'user_id' => $user->id,
        'healthcare_professional_id' => $professionals->random()->id,
        'appointment_start_time' => now()->addDays(2),
        'appointment_end_time' => now()->addDays(2)->addHour(),
    ]);

    $response = $this->actingAs($user)->getJson('/api/appointments');

    $response->assertStatus(200)
        ->assertJsonCount(10, 'data');

});

test('prevents booking the same healthcare professional in overlapping time slots', function () {

    $this->seed(HealthcareProfessionalSeeder::class);

    $user = Sanctum::actingAs(User::factory()->create());

    $professional = HealthcareProfessional::first();

    Appointment::factory()->create([
        'user_id' => $user->id,
        'healthcare_professional_id' => $professional->id,
        'appointment_start_time' => now()->addDays(2),
        'appointment_end_time' => now()->addDays(2)->addHour(),
    ]);

    $response = $this->actingAs($user)->postJson('/api/appointments', [
        'healthcare_professional_id' => $professional->id,
        'appointment_start_time' => now()->addDays(2),
        'appointment_end_time' => now()->addDays(2)->addHour(),
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'message' => 'Doctor is not available during this time. Please choose another slot',
        ]);

});

test('appointment can be cancelled 24 hours prior', function () {

    $this->seed(HealthcareProfessionalSeeder::class);

    $professional = HealthcareProfessional::first();

    $user = Sanctum::actingAs(User::factory()->create());

    $appointment = Appointment::factory()->create([
        'user_id' => $user->id,
        'healthcare_professional_id' => $professional->id,
        'appointment_start_time' => now(),
        'appointment_end_time' => now()->addDays(1),
    ]);

    $response = $this->actingAs($user)->patchJson("/api/appointments/{$appointment->id}/cancel");

    $response->assertStatus(422)
        ->assertJson([
            'message' => 'Only booked appointments can be cancelled at least 24 hours in advance.',
        ]);

});

test('only user can cancel his/her own appointments', function () {

    $this->seed(HealthcareProfessionalSeeder::class);

    $professional = HealthcareProfessional::first();

    $user = Sanctum::actingAs(User::factory()->create());

    $appointment = Appointment::factory()->create([
        'user_id' => $user->id,
        'healthcare_professional_id' => $professional->id,
        'appointment_start_time' => now(),
        'appointment_end_time' => now()->addDays(1),
    ]);

    $userTwo = Sanctum::actingAs(User::factory()->create());

    $response = $this->actingAs($userTwo)->patchJson("/api/appointments/{$appointment->id}/cancel");

    $response->assertStatus(403)
        ->assertJson([
            'message' => 'You are not authorized to modify this appointment.',
        ]);

});
