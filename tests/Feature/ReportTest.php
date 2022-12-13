<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReportTest extends TestCase
{
    /**
     * Create report test.
     *
     * @return void
     */
    public function test_create_report(): void
    {
        $user  = $this->createUser();
        $token = $user->createToken('API TOKEN')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer  $token")->postJson('/api/report', ['title' => 'fake', 'summary' => 'fake summary', 'creatorID' => $user->id]);

        $response->assertCreated();
        $response->assertJson(['status' => true, 'message' => 'Report Created Successfully']);

        $this->assertDatabaseHas('reports', ['title' => 'fake', 'summary' => 'fake summary', 'creatorID' => $user->id]);
    }

    /**
     * Read report test.
     *
     * @return void
     */
    public function test_read_report(): void
    {
        $user  = $this->createUser();
        $token = $user->createToken('API TOKEN')->plainTextToken;
        
        $report = $this->createReport($user->id);

        $response = $this->withHeader('Authorization', "Bearer  $token")->getJson("/api/report/$report->id");

        $response->assertOk();
        $response->assertJson(['status' => true, 'message' => 'Report Found Successfully', 'report' => $report->getVisible()]);
    }

    /**
     * Update report test.
     *
     * @return void
     */
    public function test_update_report(): void
    {
        $user  = $this->createUser();
        $token = $user->createToken('API TOKEN')->plainTextToken;

        $report = $this->createReport($user->id);

        $response = $this->withHeader('Authorization', "Bearer $token")->putJson("/api/report/$report->id", ['title' => 'fake2', 'summary' => 'fake summary2']);

        $response->assertOk();
        $response->assertJson(['status' => true, 'message' => 'Report Updated Successfully']);
        $this->assertDatabaseHas('reports', ['title' => 'fake2', 'summary' => 'fake summary2']);
        $this->assertDatabaseMissing('reports', [
            'title' => 'report-test',
            'summary' => 'summary test',
        ]);
    }

    /**
     * Delete report test.
     *
     * @return void
     */
    public function test_delete_report(): void
    {
        $user  = $this->createUser();
        $token = $user->createToken('API TOKEN')->plainTextToken;

        $report = $this->createReport($user->id);

        $response = $this->withHeader('Authorization', "Bearer $token")->deleteJson("/api/report/$report->id");

        $response->assertOk();
        $response->assertJson(['status' => true, 'message' => 'Report Deleted Successfully']);
        $this->assertDatabaseMissing('reports', [
            'id' => $report->id,
        ]);
    }
}
