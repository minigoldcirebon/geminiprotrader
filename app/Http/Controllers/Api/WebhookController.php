<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExpertSignal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WebhookController extends Controller
{
    /**
     * Handle expert signal webhook from external sources.
     */
    public function expertSignal(Request $request)
    {
        try {
            // Log incoming webhook
            Log::info('Expert Signal Webhook received', [
                'source' => $request->header('X-Webhook-Source'),
                'payload' => $request->all()
            ]);

            // Validate webhook payload
            $validator = Validator::make($request->all(), [
                'external_id' => 'required|string',
                'source' => 'required|string',
                'pair' => 'required|string',
                'signal_type' => 'required|in:BUY,SELL,HODL',
                'entry_price' => 'required|numeric|min:0',
                'take_profit' => 'nullable|numeric|min:0',
                'stop_loss' => 'nullable|numeric|min:0',
                'confidence_level' => 'nullable|integer|min:1|max:5',
                'risk_level' => 'nullable|in:low,medium,high',
                'timeframe' => 'nullable|string',
                'analysis_reason' => 'required|string',
                'expires_in_hours' => 'nullable|integer|min:1|max:168', // max 1 week
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if signal with external_id already exists
            $existingSignal = ExpertSignal::where('external_id', $request->external_id)
                                         ->where('webhook_source', $request->source)
                                         ->first();

            if ($existingSignal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Signal with this external_id already exists',
                    'signal_id' => $existingSignal->id
                ], 409);
            }

            // Get system user for webhook-created signals
            $systemUser = User::where('email', 'system@geminiprotrader.com')->first();
            if (!$systemUser) {
                $systemUser = User::first(); // Fallback to first user
            }

            // Calculate expiration time
            $expiresAt = null;
            if ($request->filled('expires_in_hours')) {
                $expiresAt = now()->addHours($request->expires_in_hours);
            }

            // Create new expert signal
            $signal = ExpertSignal::create([
                'external_id' => $request->external_id,
                'webhook_source' => $request->source,
                'webhook_payload' => $request->all(),
                'pair' => strtoupper($request->pair),
                'signal_type' => strtoupper($request->signal_type),
                'entry_price' => $request->entry_price,
                'take_profit' => $request->take_profit,
                'stop_loss' => $request->stop_loss,
                'confidence_level' => $request->confidence_level ?? 3,
                'risk_level' => $request->risk_level ?? 'medium',
                'timeframe' => $request->timeframe,
                'analysis_reason' => $request->analysis_reason,
                'image_url' => $request->chart_url ?? $request->image_url,
                'priority' => $request->priority ?? 0,
                'status' => 'published', // Auto-publish webhook signals
                'created_by' => $systemUser->id,
                'approved_by' => $systemUser->id,
                'published_at' => now(),
                'expires_at' => $expiresAt,
                'metadata' => $request->metadata ?? [],
            ]);

            Log::info('Expert Signal created from webhook', [
                'signal_id' => $signal->id,
                'external_id' => $signal->external_id,
                'source' => $signal->webhook_source
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Expert signal created successfully',
                'signal' => [
                    'id' => $signal->id,
                    'external_id' => $signal->external_id,
                    'pair' => $signal->pair,
                    'signal_type' => $signal->signal_type,
                    'entry_price' => $signal->entry_price,
                    'status' => $signal->status,
                    'published_at' => $signal->published_at,
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Expert Signal Webhook Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => config('app.debug') ? $e->getMessage() : 'Something went wrong'
            ], 500);
        }
    }

    /**
     * Update signal result (close signal).
     */
    public function updateSignalResult(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'external_id' => 'required|string',
                'source' => 'required|string',
                'close_price' => 'required|numeric|min:0',
                'result' => 'nullable|in:profit,loss,expired',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $signal = ExpertSignal::where('external_id', $request->external_id)
                                 ->where('webhook_source', $request->source)
                                 ->first();

            if (!$signal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Signal not found'
                ], 404);
            }

            // Close the signal
            $signal->closeSignal($request->close_price);

            Log::info('Signal closed via webhook', [
                'signal_id' => $signal->id,
                'external_id' => $signal->external_id,
                'close_price' => $request->close_price,
                'profit_loss' => $signal->profit_loss_percentage
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Signal result updated successfully',
                'signal' => [
                    'id' => $signal->id,
                    'external_id' => $signal->external_id,
                    'close_price' => $signal->actual_close_price,
                    'profit_loss_percentage' => $signal->profit_loss_percentage,
                    'signal_result' => $signal->signal_result,
                    'closed_at' => $signal->closed_at,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Update Signal Result Webhook Error', [
                'error' => $e->getMessage(),
                'payload' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => config('app.debug') ? $e->getMessage() : 'Something went wrong'
            ], 500);
        }
    }
}
