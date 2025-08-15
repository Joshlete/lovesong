<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class S3ConnectionTest extends TestCase
{
    /**
     * Test S3 connection and basic operations.
     */
    public function test_s3_connection_works(): void
    {
        // Skip this test if AWS credentials are not configured
        if (empty(config('filesystems.disks.s3.key')) || empty(config('filesystems.disks.s3.secret'))) {
            $this->markTestSkipped('AWS S3 credentials not configured');
        }

        $s3 = Storage::disk('s3');
        
        // Test 1: Basic disk access
        $this->assertNotNull($s3, 'S3 disk should be accessible');

        // Test 2: Upload a test file
        $testContent = 'Test file content created at ' . now();
        $testPath = 'test/connection-test-' . time() . '.txt';
        
        $uploaded = $s3->put($testPath, $testContent);
        $this->assertTrue($uploaded, 'File should upload successfully');

        // Test 3: Verify file exists
        $this->assertTrue($s3->exists($testPath), 'Uploaded file should exist');

        // Test 4: Read file back
        $retrievedContent = $s3->get($testPath);
        $this->assertEquals($testContent, $retrievedContent, 'Retrieved content should match uploaded content');

        // Test 5: Generate temporary URL
        $tempUrl = $s3->temporaryUrl($testPath, now()->addMinutes(10));
        $this->assertStringContainsString('amazonaws.com', $tempUrl, 'Temporary URL should be valid AWS URL');

        // Test 6: Clean up - delete test file
        $deleted = $s3->delete($testPath);
        $this->assertTrue($deleted, 'File should be deleted successfully');
        
        // Verify file is actually deleted
        $this->assertFalse($s3->exists($testPath), 'File should no longer exist after deletion');
    }

    /**
     * Test that we can handle S3 errors gracefully.
     */
    public function test_s3_handles_missing_files_gracefully(): void
    {
        // Skip this test if AWS credentials are not configured
        if (empty(config('filesystems.disks.s3.key')) || empty(config('filesystems.disks.s3.secret'))) {
            $this->markTestSkipped('AWS S3 credentials not configured');
        }

        $s3 = Storage::disk('s3');
        $nonExistentPath = 'test/non-existent-file-' . time() . '.txt';

        // Test that checking for non-existent file returns false
        $this->assertFalse($s3->exists($nonExistentPath), 'Non-existent file should not exist');

        // Test that deleting non-existent file doesn't throw error
        $this->assertTrue($s3->delete($nonExistentPath), 'Deleting non-existent file should return true');
    }
}