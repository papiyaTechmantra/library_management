<?php

use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\Career;
use App\Models\Category;
use App\Models\Product;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if (!function_exists('slugGenerate')) {
    function slugGenerate($title, $table) {
        $slug = Str::slug($title, '-');
        $slugExistCount = DB::table($table)->where('title', $title)->count();
        if ($slugExistCount > 0) $slug = $slug . '-' . ($slugExistCount + 1);
        return $slug;
    }
}
if (!function_exists('slugGenerateUpdate')) {
    function slugGenerateUpdate($title, $table, $productId) {
        $slug = Str::slug($title, '-');
        $slugExistCount = DB::table($table)->where('title', $title)->where('id', '!=', $productId)->count();
        if ($slugExistCount > 0) $slug = $slug . '-' . ($slugExistCount + 1);
        return $slug;
    }
}

if(!function_exists('pageContentSlug')){
    function pageContentSlug($page ,$table){
        $slug = Str::slug($page , '-');
        $slugExistCount = DB::table($table)->where('page',$page )->count();
        if($slugExistCount>0)$slug = $slug . '-' . ($slugExistCount +  1);
        return $slug;
    }
}

if(!function_exists('pageContentSlugUpdate')){
    function  pageContentSlugUpdate($page, $table ,$pageContentId ){
        $slug = Str::slug($page, '-');
        $slugExistCount = DB::table($table)->where('page', $page)->where('id', '!=', $pageContentId)->count();
        if($slugExistCount>0)$slug = $slug . '-' . ($slugExistCount +  1);
        return $slug;
    }
}

if (!function_exists('EmailVerification')) {
    function EmailVerification($email, $title) {
        try {
            $rand = rand(111111, 999999);
            $mail = new PHPMailer(true);
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.netcorecloud.net';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'technoapi';
            $mail->Password   = 'TIGws@2024';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            //Recipients
            $mail->setFrom('admin@technoindiagroupworldschool.com', 'TIGWS');
            $mail->addAddress($email, $title);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Application OTP Request | Techno India Group World School';
            $mail->Body = '<html><body>
                <p>Hello <strong>'.$title.'</strong></p>
                <p>Enter the OTP <strong>'.$rand.'</strong> on your Techno India Group World School application form to validate your email address.</p>
            </body></html>';

            // Send the email
            if($mail->send()){
                return $rand; // Return the generated random number on success
            } else {
                return false; // Return false if sending fails
            }
           
        } catch (Exception $e) {
            return false;
        }
    }
}
if (!function_exists('FinalFormSubmitMail')) {
    function FinalFormSubmitMail($careerId) {
        $data = Career::findOrFail($careerId);
        try {
            $mail = new PHPMailer(true);
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.netcorecloud.net';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'technoapi';
            $mail->Password   = 'TIGws@2024';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            //Recipients
            $mail->setFrom('admin@technoindiagroupworldschool.com', 'TIGWS');
            $mail->addAddress($data->email, $data->name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Application Form Received';
            $mail->Body = '<html><body>
                <p>Thank you for applying for the position of <em>'.$data->applied_post.'</em> at Techno India Group World School.</p>
                <p>Your application has been received.</p>
                <p>Please note your Application ID: <strong>'.$data->registration_id.'</strong>.</p>
                <p>In case of any query related to your application, please feel free to contact us on <strong>&lt;mobile number&gt;</strong>.</p>
            </body></html>';

            // Send the email
            if($mail->send()){
                return true; // Return the generated random number on success
            } else {
                return false; // Return false if sending fails
            }
           
        } catch (Exception $e) {
            return false;
        }
    }
}

// generate unique alpha numeric digit
if (!function_exists('mt_rand_custom')) {
    function mt_rand_custom(int $length_of_string) {
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($str_result), 0, $length_of_string);
    }
}

// generate human readable date
if (!function_exists('h_date')) {
    function h_date(string $date) {
        return date('j F Y, g:i A', strtotime($date));
    }
}

// calculate discount
if (!function_exists('discountCalculate')) {
    function discountCalculate($offerPrice, $price) {
        if ($offerPrice < $price && $offerPrice != $price) {
            $diff = $price - $offerPrice;
            return ceil(($diff / $price) * 100).'%';
        } else {
            return false;
        }
    }
}

// generate sequencial order number
if (!function_exists('orderNumberGenerate')) {
    function orderNumberGenerate() {
        $orderExists = Order::select('id')->latest('id')->first();
        if(empty($orderExists)) $orderSeq = 1;
        else $orderSeq = (int) $orderExists->id + 1;

        $ordNo = sprintf("%'.05d", $orderSeq);
        $order_no = date('y').$ordNo;

        return $order_no;
    }
}

// file upload
if (!function_exists('fileUpload')) {
    function fileUpload($file, $folder = 'image') {
        $fileName = mt_rand_custom(20);
        $fileExtension = $file->getClientOriginalExtension();
        $uploadPath = 'uploads/'.$folder.'/';
        $filePath = $uploadPath.$fileName.'.'.$fileExtension;
        $tmpPath = $file->getRealPath();
        $mimeType = \File::mimeType($file);

        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 666, true);
        }

        // dd($fileName, $fileExtension, $uploadPath, $filePath);

        // Handle images
        if (in_array($fileExtension, ['jpeg', 'png', 'jpg', 'gif', 'webp'])) {
            if (
                $fileExtension == "jpeg" ||
                $fileExtension == "png" ||
                $fileExtension == "jpg" ||
                $fileExtension == "webp"
            ) {
                // THUMBNAIL CREATE HERE
                $smallImagePath = $uploadPath.$fileName.'_small-thumb_'.'.'.$fileExtension;
                $mediumImagePath = $uploadPath.$fileName.'_medium-thumb_'.'.'.$fileExtension;
                $largeImagePath = $uploadPath.$fileName.'_large-thumb_'.'.'.$fileExtension;

                createThumbnail($tmpPath, $smallImagePath, null, 100);
                createThumbnail($tmpPath, $mediumImagePath, null, 250);
                createThumbnail($tmpPath, $largeImagePath, null, 500);
                $largeOne = $largeImagePath;
            } else {
                // THUMBNAIL CREATE HERE
                $smallImagePath = $uploadPath.$fileName.'_small-thumb_'.'.jpg';
                $mediumImagePath = $uploadPath.$fileName.'_medium-thumb_'.'.jpg';

                createThumbnail($tmpPath, $smallImagePath, null, 100);
                createThumbnail($tmpPath, $mediumImagePath, null, 250);
                // $file->move(public_path($uploadPath), $fileName.'.'.$fileExtension);
                $file->move($uploadPath, $fileName.'.'.$fileExtension);
                $largeOne = $filePath;
            }

            $resp = [
                'type' => $mimeType,
                'extension' => $fileExtension,
                'file' => [
                    $smallImagePath,
                    $mediumImagePath,
                    $largeOne
                ],
            ];
        }
        // Handle PDF, DOC, DOCX
        elseif (in_array($fileExtension, ['pdf', 'doc', 'docx'])) {
            // Move the file to the storage location
            $file->move($uploadPath, $fileName.'.'.$fileExtension);
            
            $resp = [
                'type' => $mimeType,
                'extension' => $fileExtension,
                'file' => [
                    $filePath
                ],
            ];
        }
        // Handle other file types (fallback)
        else {
            // $file->move(public_path($uploadPath), $fileName.'.'.$fileExtension);
            $file->move($uploadPath, $fileName.'.'.$fileExtension);

            $resp = [
                'type' => $mimeType,
                'extension' => $fileExtension,
                'file' => [
                    $filePath
                ],
            ];
        }

        return $resp;
    }
}


if (!function_exists('createThumbnail')) {
    function createThumbnail($tmpPath, $filePath, $width, $height)
    {
        $img = Image::make($tmpPath);
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->save($filePath);
    }
}

if (!function_exists('catLeveltoProducts')) {
    function catLeveltoProducts($cat1_id) {
        $products = [];
        $lvl2_cats = Category::select('id')->where('parent_id', $cat1_id)->where('status', 1)->get();

        if (!empty($lvl2_cats) && count($lvl2_cats) > 0) {
            foreach ($lvl2_cats as $key => $lvl2_cat) {
                $lvl3_cats = Category::select('id')->where('parent_id', $lvl2_cat['id'])->where('status', 1)->get();

                if (!empty($lvl3_cats) && count($lvl3_cats) > 0) {
                    foreach ($lvl3_cats as $key => $lvl3_cat) {
                        $products = Product::select('id', 'slug', 'title')->where('category_id', $lvl3_cat['id'])->where('status', '!=', 2)->get();
                    }
                }
            }
        }

        return $products;
    }
}

if (!function_exists('catLeve2toProducts')) {
    function catLeve2toProducts($cat2_id) {
        $products = [];
        $lvl3_cats = Category::select('id')->where('parent_id', $cat2_id)->where('status', 1)->get();

        if (!empty($lvl3_cats) && count($lvl3_cats) > 0) {
            foreach ($lvl3_cats as $key => $lvl3_cat) {
                $products = Product::select('id', 'slug', 'title')->where('category_id', $lvl3_cat['id'])->where('status', '!=', 2)->get();
            }
        }

        return $products;
    }
}

if (!function_exists('lead_page')) {
    function lead_page($page) {
        if (str_contains($page, 'contact')) {
            return 'Contact page';
        }
        elseif (str_contains($page, 'product')) {
            $explodedProduct = explode('/', $page);
            // dd($explodedProduct);
            $explodedProductCount = count($explodedProduct);

            return $explodedProduct[4];
        }
    }
}

if (!function_exists('categoryLevelFinder')) {
    function categoryLevelFinder($id) {
        $data = Category::findOrFail($id);
        if ($data->parent_id == 0) {
            return 'Level 1';
        } else {
            $parentData = Category::where('id', $data->parent_id)->first();

            if(!empty($parentData)) {
                if ($parentData->parent_id == 0) {
                    return 'Level 2';
                } else {
                    // return $parentData->parent_id;
                    $parentData2 = Category::where('id', $parentData->parent_id)->first();
    
                    if(!empty($parentData2)) {
                        if ($parentData2->parent_id == 0) {
                            return 'Level 3';
                        } else {
                            return 'Other level';
                        }
                    } else {
                        return 'Invalid';
                    }
                }
            } else {
                return 'Invalid';
            }
        }
    }
}
