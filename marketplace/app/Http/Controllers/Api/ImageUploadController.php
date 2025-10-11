<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploadController extends Controller
{
    protected ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Upload product image
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadProductImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'product_id' => 'nullable|exists:products,id',
        ]);

        try {
            $file = $request->file('image');
            $filename = $this->generateFilename($file);
            
            // Create different sizes
            $sizes = [
                'original' => null, // Original size
                'large' => 1200,    // Product detail page
                'medium' => 600,    // Product listing
                'thumb' => 300,     // Thumbnails
            ];

            $urls = [];
            
            foreach ($sizes as $sizeName => $width) {
                $image = $this->imageManager->read($file);
                
                if ($width) {
                    $image->scale(width: $width);
                }
                
                // Optimize quality
                $encoded = $image->toJpeg(quality: 85);
                
                $path = "products/{$sizeName}/{$filename}";
                Storage::disk('public')->put($path, $encoded);
                
                $urls[$sizeName] = Storage::disk('public')->url($path);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product image uploaded successfully',
                'data' => [
                    'filename' => $filename,
                    'urls' => $urls,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload vendor logo
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadVendorLogo(Request $request): JsonResponse
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048', // 2MB max
            'vendor_id' => 'required|exists:vendors,id',
        ]);

        try {
            $file = $request->file('logo');
            $filename = $this->generateFilename($file);
            
            $image = $this->imageManager->read($file);
            
            // Resize to square for logo
            $image->cover(400, 400);
            $encoded = $image->toJpeg(quality: 90);
            
            $path = "vendors/logos/{$filename}";
            Storage::disk('public')->put($path, $encoded);
            
            $url = Storage::disk('public')->url($path);

            return response()->json([
                'success' => true,
                'message' => 'Vendor logo uploaded successfully',
                'data' => [
                    'filename' => $filename,
                    'url' => $url,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload logo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload vendor banner
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadVendorBanner(Request $request): JsonResponse
    {
        $request->validate([
            'banner' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
            'vendor_id' => 'required|exists:vendors,id',
        ]);

        try {
            $file = $request->file('banner');
            $filename = $this->generateFilename($file);
            
            $image = $this->imageManager->read($file);
            
            // Resize banner to 1920x400
            $image->cover(1920, 400);
            $encoded = $image->toJpeg(quality: 85);
            
            $path = "vendors/banners/{$filename}";
            Storage::disk('public')->put($path, $encoded);
            
            $url = Storage::disk('public')->url($path);

            return response()->json([
                'success' => true,
                'message' => 'Vendor banner uploaded successfully',
                'data' => [
                    'filename' => $filename,
                    'url' => $url,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload banner',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload user avatar
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048', // 2MB max
        ]);

        try {
            $file = $request->file('avatar');
            $user = $request->user();
            $filename = 'avatar_' . $user->id . '_' . time() . '.jpg';
            
            $image = $this->imageManager->read($file);
            
            // Create circular avatar
            $image->cover(300, 300);
            $encoded = $image->toJpeg(quality: 90);
            
            $path = "avatars/{$filename}";
            
            // Delete old avatar if exists
            if ($user->avatar) {
                $oldPath = str_replace(Storage::disk('public')->url(''), '', $user->avatar);
                Storage::disk('public')->delete($oldPath);
            }
            
            Storage::disk('public')->put($path, $encoded);
            $url = Storage::disk('public')->url($path);

            // Update user avatar
            $user->update(['avatar' => $url]);

            return response()->json([
                'success' => true,
                'message' => 'Avatar uploaded successfully',
                'data' => [
                    'filename' => $filename,
                    'url' => $url,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload avatar',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload category image
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadCategoryImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // 2MB max
            'category_id' => 'required|exists:categories,id',
        ]);

        try {
            $file = $request->file('image');
            $filename = $this->generateFilename($file);
            
            $image = $this->imageManager->read($file);
            $image->scale(width: 800);
            $encoded = $image->toJpeg(quality: 85);
            
            $path = "categories/{$filename}";
            Storage::disk('public')->put($path, $encoded);
            
            $url = Storage::disk('public')->url($path);

            return response()->json([
                'success' => true,
                'message' => 'Category image uploaded successfully',
                'data' => [
                    'filename' => $filename,
                    'url' => $url,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload review image
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadReviewImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:3072', // 3MB max
            'review_id' => 'nullable|exists:reviews,id',
        ]);

        try {
            $file = $request->file('image');
            $filename = $this->generateFilename($file);
            
            $image = $this->imageManager->read($file);
            $image->scale(width: 800);
            $encoded = $image->toJpeg(quality: 85);
            
            $path = "reviews/{$filename}";
            Storage::disk('public')->put($path, $encoded);
            
            $url = Storage::disk('public')->url($path);

            return response()->json([
                'success' => true,
                'message' => 'Review image uploaded successfully',
                'data' => [
                    'filename' => $filename,
                    'url' => $url,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete image
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteImage(Request $request): JsonResponse
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            $path = $request->path;
            
            // Remove base URL if present
            $path = str_replace(Storage::disk('public')->url(''), '', $path);
            
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Image deleted successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Image not found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload multiple product images
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadProductImages(Request $request): JsonResponse
    {
        $request->validate([
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'product_id' => 'nullable|exists:products,id',
        ]);

        try {
            $uploadedImages = [];

            foreach ($request->file('images') as $file) {
                $filename = $this->generateFilename($file);
                
                $sizes = [
                    'original' => null,
                    'large' => 1200,
                    'medium' => 600,
                    'thumb' => 300,
                ];

                $urls = [];
                
                foreach ($sizes as $sizeName => $width) {
                    $image = $this->imageManager->read($file);
                    
                    if ($width) {
                        $image->scale(width: $width);
                    }
                    
                    $encoded = $image->toJpeg(quality: 85);
                    $path = "products/{$sizeName}/{$filename}";
                    Storage::disk('public')->put($path, $encoded);
                    
                    $urls[$sizeName] = Storage::disk('public')->url($path);
                }

                $uploadedImages[] = [
                    'filename' => $filename,
                    'urls' => $urls,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => count($uploadedImages) . ' images uploaded successfully',
                'data' => $uploadedImages
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload images',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate unique filename
     *
     * @param $file
     * @return string
     */
    protected function generateFilename($file): string
    {
        $extension = $file->getClientOriginalExtension();
        return Str::random(40) . '_' . time() . '.' . $extension;
    }
}
