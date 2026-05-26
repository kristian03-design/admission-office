<?php

namespace Tests\Feature;

use App\Models\ContactInquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class InputSanitizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    /**
     * Test that script tags and HTML injection are successfully stripped by the global middleware.
     */
    public function test_incoming_request_is_globally_sanitized_to_prevent_html_and_script_injection(): void
    {
        $payload = [
            'first_name' => 'John <script>alert("XSS")</script>',
            'last_name' => 'Doe <iframe src="bad"></iframe>',
            'email' => 'john.doe@example.com',
            'subject' => 'Help <body onload="hack()">me</body>',
            'message' => 'Injecting high-level script tag: <script type="text/javascript">window.location="http://bad.com"</script> Clean me!',
            'form_started_at' => now()->subSeconds(10)->timestamp,
            '_hp' => '',
        ];

        $this->postJson('/api/contact', $payload)->assertCreated();

        // Check that the saved database record contains sanitized string contents with HTML stripped entirely
        $inquiry = ContactInquiry::where('email', 'john.doe@example.com')->first();

        $this->assertNotNull($inquiry);
        
        // Assert HTML tags and script/iframe payloads are removed
        $this->assertEquals('John', $inquiry->first_name);
        $this->assertEquals('Doe', $inquiry->last_name);
        $this->assertEquals('Help me', $inquiry->subject);
        $this->assertEquals('Injecting high-level script tag:  Clean me!', $inquiry->message);
    }
}
