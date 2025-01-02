<?php

test('oauth/token is up', function () {
    $response = $this->postJson('oauth/token', []);
    $response->assertStatus(400);
});