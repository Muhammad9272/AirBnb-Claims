<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Blog;
use App\Models\Service;
use Cache;
use DB;

class HomeController extends Controller
{   
    public function __construct(Request $request)
    {
        $this->middleware('auth')->only(['pickInterests', 'pickInterestStore']);
        $this->request = $request;
    }

    /**
     * Show the home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all active subscription plans
        $subscriptionPlans = \App\Models\SubPlan::where('status', 1)
            // ->orderBy('is_featured', 'desc')
            ->orderBy('price', 'asc')
            ->get();
        
        // Format features into arrays for each plan
        foreach ($subscriptionPlans as $plan) {
            $plan->feature_list = $plan->features ? explode("\n", $plan->features) : [];
            
            // Format claims limit for display
            if ($plan->claims_limit === null) {
                $plan->claims_limit_text = 'Unlimited';
            } else {
                $plan->claims_limit_text = $plan->claims_limit . ' per ' . $this->formatInterval($plan->interval);
            }
        }
        
        return view('front.index', compact('subscriptionPlans'));
    }
    
    /**
     * Format the subscription interval in human-readable form
     */
    private function formatInterval($interval)
    {
        switch ($interval) {
            case 'weekly':
                return 'week';
            case 'monthly':
                return 'month';
            case 'quarterly':
                return 'quarter';
            case 'biannually':
                return '6 months';
            case 'yearly':
                return 'year';
            default:
                return $interval;
        }
    }

    public function page($slug)
    {
        $page =  DB::table('pages')->where('slug',$slug)->where('status',1)->first();
        if(empty($page))
        {
            return view('errors.404');
        }

        return view('front.page',compact('page'));
    }
    public function pricing()
    {
        return view('front.pricing');
    }
    public function employGuide()
    {
       return view('front.employs-guide');
    }
    public function employDetail()
    {
        return view('front.codetail');
    }


 
    public function getSubcategories($categoryId)
    {
       $subcategories = ProductCategory::where('parent_id', $categoryId)->get();
        return response()->json([
            'success' => true,
            'subcategories' => $subcategories
        ]);
    }



    public function dropzoneStoreMedia(Request $request)
    {
        $path = storage_path('tmp/uploads');       
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');
        $name = uniqid() . '_' . trim($file->getClientOriginalName());
        $file->move($path, $name);
        
        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function pdff(Request $request)
    {
         

        // $quiz = QuizBank::where('slug', $quiz_slug)->active()->firstOrFail();        
        // if($quiz->pdf_file){
            // $token = $this->generateToken();
            return view('front.test', ['token' => '22']);
        // }else{
        //     dd("pdf not found");
        // }
       
    }

    public function servePdf(Request $request, $token)
    {
        // if (!Cache::get($token)) {
        //     abort(403, 'Invalid or expired token');
        // }
        // $quiz = QuizBank::where('slug', $token)->active()->firstOrFail();

        // $imagepath='assets/dynamic/images/quiz/pdf_files/'.$quiz->pdf_file;

        $imagepath = 'assets\sample2.pdf';
        $pdf = new Fpdi();
        $pdf->setSourceFile(public_path($imagepath));
        $totalPages = $pdf->setSourceFile(public_path($imagepath));
  
        // Assuming you want the first 3 pages for now, 
        // Adjust accordingly based on your requirements
        $pagesToExtract = $totalPages;

        for ($pageNo = 1; $pageNo <= $pagesToExtract; $pageNo++) {
            $pdf->AddPage();
            $pdf->setSourceFile(public_path($imagepath));
            $pdf->useTemplate($pdf->importPage($pageNo));
        }

        $pdfContent = $pdf->Output('S');
       
        return response($pdfContent, 200);
    }

}
