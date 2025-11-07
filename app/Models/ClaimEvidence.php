<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimEvidence extends Model
{
    use HasFactory;
    
    // Explicit table name
    protected $table = 'claim_evidences';

    protected $fillable = [
        'claim_id',
        'file_path',
        'file_name',
        'file_type',
        'description',
        'type',
        'uploaded_by',
        'is_video',
        'video_duration',
        'chunk_status'
    ];

    /**
     * Get the claim that owns the evidence.
     */
    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    /**
     * Get the user who uploaded the evidence.
     */
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Check if the file is an image.
     */
    public function isImage()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $extension = pathinfo($this->file_name, PATHINFO_EXTENSION);
        return in_array(strtolower($extension), $imageExtensions);
    }

    /**
     * Check if the file is a video.
     */
    public function isVideo()
    {
        $videoExtensions = ['mp4', 'mov', 'avi', 'wmv'];
        $extension = pathinfo($this->file_name, PATHINFO_EXTENSION);
        return in_array(strtolower($extension), $videoExtensions);
    }
    
    /**
     * Get the appropriate icon class for file type.
     */
    public function getIconClassAttribute()
    {
        $extension = strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));
        
        return match($extension) {
            'pdf' => 'ri-file-pdf-line',
            'doc', 'docx' => 'ri-file-word-line',
            'xls', 'xlsx' => 'ri-file-excel-line',
            'ppt', 'pptx' => 'ri-file-ppt-line',
            'jpg', 'jpeg', 'png', 'gif', 'webp' => 'ri-image-line',
            'mp4', 'mov', 'avi', 'wmv' => 'ri-video-line',
            default => 'ri-file-line',
        };
    }
}
