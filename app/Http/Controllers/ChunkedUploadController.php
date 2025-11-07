<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ClaimEvidence;
use App\Models\ClaimComment;
use App\Services\NotificationService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class ChunkedUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required',
            'chunk' => 'required|integer',
            'totalChunks' => 'required|integer',
            'filename' => 'required|string',
            'claim_id' => 'required'
        ]);

        try {
            $chunk = $request->file('file');
            $chunkNumber = $request->input('chunk');
            $totalChunks = $request->input('totalChunks');
            $originalFilename = $request->input('filename');

            // Create temporary directory if it doesn't exist
            $tempPath = storage_path('app/temp/chunks/' . $request->claim_id);
            if (!file_exists($tempPath)) {
                mkdir($tempPath, 0777, true);
            }

            // Move the chunk to temporary storage
            $chunk->move($tempPath, "chunk_{$chunkNumber}");

            // If this is the last chunk
            if ($chunkNumber == $totalChunks - 1) {
                $finalFile = fopen($tempPath . '/' . $originalFilename, 'wb');

                for ($i = 0; $i < $totalChunks; $i++) {
                    $chunkFile = $tempPath . "/chunk_{$i}";
                    $chunk = file_get_contents($chunkFile);
                    fwrite($finalFile, $chunk);
                    unlink($chunkFile);
                }
                fclose($finalFile);

                $finalSize = filesize($tempPath . '/' . $originalFilename);
                if ($finalSize > 30 * 1024 * 1024) {
                    unlink($tempPath . '/' . $originalFilename);
                    return response()->json(['error' => 'Video size exceeds 30MB limit'], 400);
                }

                $destination = public_path('assets/dynamic/claims/videos/');
                if (!file_exists($destination)) {
                    mkdir($destination, 0777, true);
                }

                $filename = time() . '_' . str_replace(' ', '', $originalFilename);
                rename($tempPath . '/' . $originalFilename, $destination . $filename);

                // For new claims, store in session
                if ($request->claim_id === 'new') {
                    session(['pendingVideo' => [
                        'filename' => $originalFilename,
                        'path' => $filename,
                        'description' => $request->description
                    ]]);

                    return response()->json([
                        'success' => true,
                        'filename' => $filename
                    ]);
                }

                // Existing claim case
                $claim = \App\Models\Claim::find($request->claim_id);
                $evidence = ClaimEvidence::create([
                    'claim_id' => $claim->id,
                    'file_path' => $filename,
                    'file_name' => $originalFilename,
                    'file_type' => pathinfo($originalFilename, PATHINFO_EXTENSION),
                    'type' => 'other',
                    'is_video' => true,
                    'uploaded_by' => auth()->id(),
                    'chunk_status' => 'completed',
                    'video_duration' => null 
                ]);

                // Clean up temp directory
                $files = glob($tempPath . '/*'); 
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
                rmdir($tempPath);

                $comment = new ClaimComment();
                $comment->claim_id = $claim->id;
                $comment->user_id = Auth::id();
                $comment->comment = 'Added 1 new piece of evidence';
                if ($request->description) {
                    $comment->comment .= ': ' . $request->description;
                }
                $comment->save();
                
                // Notify admin about new evidence
                NotificationService::newEvidence($claim, 1);
                return response()->json([
                    'success' => true,
                    'evidence_id' => $evidence->id
                ]);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteEvidence(Request $request, $id)
    {
        if($id === 'delete_existing') {
            $existingFilename = $request->input('file_name');
            $videoPath = public_path('assets/dynamic/claims/videos/' . $existingFilename);
            if (File::exists($videoPath)) {
                File::delete($videoPath);
            }

            session()->remove('pendingVideo');
            return response()->json(['success' => true]);
        }

        $evidence = ClaimEvidence::find($id);
        $evidenceComment = ClaimComment::where('claim_id', $evidence->claim_id)->first();
        if (!$evidence) {
            return response()->json(['success' => false, 'message' => 'Evidence not found.'], 404);
        }

        // Only allow the user who owns the claim or an admin to delete
        $claim = $evidence->claim;
        $user = auth()->user();
        if (!$claim || ($user->id !== $claim->user_id && !$user->hasRole('admin'))) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        // Delete the file if it's a video
        if ($evidence->is_video) {
            $videoPath = public_path('assets/dynamic/claims/videos/' . $evidence->file_path);
            if (File::exists($videoPath)) {
                File::delete($videoPath);
            }
        } else {
            // For other files, you may want to handle deletion as well
            $filePath = public_path('assets/dynamic/claims/' . $evidence->file_path);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
        $evidenceComment->delete();
        $evidence->delete();

        return response()->json(['success' => true]);
    }

}
