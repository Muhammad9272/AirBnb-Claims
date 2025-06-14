<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Helpers;
use App\CentralLogics\UserAccess;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Company;
use App\Models\QuizBank;
use App\Models\QuizBankManagement;
use App\Models\QuizCompletion;
use App\Models\QuizProgress;
use App\Models\SubPlan;
use App\Models\UserQuizBankAccess;
use DataTables;
use Illuminate\Http\Request;
use Session;
class QuizManagementController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
    }

    public function quizPracticeDatatables(Request $request)
    {
        $company = null;
        $company_slug=$request->company_slug;
        $selfRecorded=$request->selfRecorded;
        if ($company_slug ) {
            // Find company based on slug
            $company = Company::where('slug', $company_slug)->firstOrFail();
        }

        $query = QuizBankManagement::active()
            ->when($company, function ($query, $company) {
                return $query->where('company_id', $company->id);
            });

        if ($selfRecorded) {
             $query->where('assessment_type', 'Self-Recorded');
        }
        $query=$query->orderBy(\DB::raw('(SELECT name FROM companies WHERE companies.id = quiz_bank_management.company_id)'), 'asc')->get();
        return DataTables::of($query)
                            
                           ->addColumn('company_id', function(QuizBankManagement $data) {
                                return 
                                '<div class="d-flex align-items-center gap-2">
                                    <img height="37" src="'.Helpers::image($data->company ? $data->company->logo : '', 'company/logo/').'" alt="...">
                                    <span>
                                         '.($data->company ? $data->company->name : '').'
                                    </span>
                                </div>';
                                
                            })
                           
                            ->addColumn('min_membership', function(QuizBankManagement $data) {
                                $min_member=UserAccess::findSuitablePlan($data->assessment_type);
                                return $min_member;
                            })

                            ->addColumn('action', function(QuizBankManagement $data) {
                                $favoriteComponent = view('includes.favorite', [
                                    'favdata' => $data,
                                    'type' => 'QuizBankManagement',
                                ])->render();
                                return '<div class="d-flex align-items-center  justify-content-end justify-content-xl-center gap-3">
                                    <div class="fs-1 ts-heart-container-wrapper ">
                                        ' . $favoriteComponent . '
                                    </div>
                                    <a class="btn btn-primary"
                                        href="'.route('user.quiz.management.show', $data->slug) .'">Free
                                        Trial</a>
                                </div>';
                               
                            }) 
                            ->rawColumns(['company_id','min_membership','action'])
                            ->toJson(); //--- Returning Json Data To Client Side


    }
    public function quizPractice(Request $request, $company_slug = null)
    { 
        // Get all unique positions and assessment stages
        $uniq_positions = QuizBankManagement::active()->whereNotNull('position')->select('position')->distinct()->pluck('position');
        $uniq_assessmentStages = QuizBankManagement::active()->whereNotNull('assessment_stage')->select('assessment_stage')->distinct()->pluck('assessment_stage');
        $uniq_locations = QuizBankManagement::active()
            ->whereNotNull('location')
            ->select('location')
            ->distinct()
            ->pluck('location');
        $uniq_quiz_programs = QuizBankManagement::active()
            ->whereNotNull('program')
            ->select('program')
            ->distinct()
            ->pluck('program');
        $subplans = SubPlan::active()->orderBy('price','asc')->get();

        // $quizmanagements->appends(['search' => $search]);
        $banner=Banner::active()->where('for_section','section_quiz_practice')->first();

        $routeName = \Route::currentRouteName();
        $selfRecorded=false;
        if ($routeName === 'user.quiz.management.index.selfrecord') {
            $selfRecorded=true;
        }

        return view('user.quizmanagement.quiz-practice', compact('company_slug','uniq_positions','uniq_assessmentStages','uniq_locations','request','banner','uniq_quiz_programs','subplans','selfRecorded'));
       
    }

    public function quizPracticold(Request $request, $company_slug = null)
    {   

        // Get all unique positions and assessment stages
        $uniq_positions = QuizBankManagement::active()->whereNotNull('position')->select('position')->distinct()->pluck('position');
        $uniq_assessmentStages = QuizBankManagement::active()->whereNotNull('assessment_stage')->select('assessment_stage')->distinct()->pluck('assessment_stage');
        $uniq_locations = QuizBankManagement::active()
            ->whereNotNull('location')
            ->select('location')
            ->distinct()
            ->pluck('location');
        $uniq_quiz_programs = QuizBankManagement::active()
            ->whereNotNull('program')
            ->select('program')
            ->distinct()
            ->pluck('program');
        $subplans = SubPlan::active()->orderBy('price','asc')->get();

        
        // Retrieve the filters from the request
        $search = $request->get('search');        
        $quiz_position = $request->get('quiz_position');
        $quiz_assessment_stage = $request->get('quiz_assessment_stage');
        $quiz_assessment_type = $request->get('quiz_assessment_type');
        $min_membership = $request->get('min_membership');
        $quiz_program = $request->get('quiz_program');
        $location = $request->get('quiz_location');

        $company = null;
        if ($company_slug ) {
            // Find company based on slug
            $company = Company::where('slug', $company_slug)->firstOrFail();
        }

        $query = QuizBankManagement::active()
            ->when($company, function ($query, $company) {
                return $query->where('company_id', $company->id);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%')
                          ->orWhereHas('company', function ($query) use ($search) {
                              $query->where('name', 'like', '%'.$search.'%');
                          });
                });
            })
            ->when($quiz_position, function ($query, $quiz_position) {
                return $query->where('position', $quiz_position);
            })
            ->when($quiz_program, function ($query, $quiz_program) {
                return $query->where('program', $quiz_program);
            })
            ->when($location, function ($query, $location) {
                return $query->where('location', $location);
            })
            ->when($quiz_assessment_type, function ($query, $quiz_assessment_type) {
                return $query->where('assessment_type', $quiz_assessment_type);
            });   


            // ->when($min_membership, function ($query, $min_membership) {
            //             return $query->where(function ($query) use ($min_membership) {
            //                 $assessmentType = \DB::raw('quiz_bank_management.assessment_type'); // Use raw expression to prevent SQL injection
            //                 $pack = UserAccess::findSuitablePlan($assessmentType);

            //                 $query->where('assessment_type', $pack);
            //             });
            //         });
            // ->active()
            // ->latest();
            $query->orderBy(\DB::raw('(SELECT name FROM companies WHERE companies.id = quiz_bank_management.company_id)'), 'asc');

            $routeName = \Route::currentRouteName();
            if ($routeName === 'user.quiz.management.index.selfrecord') {
                 $query->where('assessment_type', 'Self-Recorded');
            }

            $quizmanagements=$query->paginate(20);

        $quizmanagements->appends(request()->except('page'));
        // $quizmanagements->appends(['search' => $search]);
        $banner=Banner::active()->where('for_section','section_quiz_practice')->first();

        return view('user.quizmanagement.quiz-practice', compact('quizmanagements','search','company_slug','uniq_positions','uniq_assessmentStages','uniq_locations','request','banner','uniq_quiz_programs','subplans'));
    }

    // public function quizList()
    // {
    //     return view('user.quizmanagement.quizlist');
    // }
    public function quizBank(Request $request, $quiz_management_slug)
    {   
        $activated=$request->activate;
        $quizbankmanagement = QuizBankManagement::where('slug', $quiz_management_slug)->active()->latest()->firstOrFail();

        $quizs = QuizBank::where('quizbankmanagement_id', $quizbankmanagement->id)->active()->orderBy('created_at', 'asc')->orderBy('quiz_group')->get();
        $quizGroups = $quizs->groupBy('quiz_group');

        if($activated){ //To activate quizbank
              $response=UserAccess::hasAccess(auth()->user(),'quizbank',$quizbankmanagement->id,$activatePositionCheck=1);
              $msgtype=$response['access']?'success':'error';

              if($msgtype==="success" || isset($response['already_opened_position'])){

                if(isset($response['already_opened_position'])){
                    Session::flash('already_opened_position',true);      
                }
                return redirect()->route('user.quiz.management.show', $quiz_management_slug)->with($msgtype,$response['message']);
                
              }
              return redirect()->route('user.pricing')->with($msgtype,$response['message']);       
        }

        //dd(767);
        $response=UserAccess::hasAccess(auth()->user(),'quizbank',$quizbankmanagement->id);
        $UserAccess=$response['access']?true:false;//If user has access to quizbank
        $canAccess=isset($response['can_access'])?true:false;
        $alreadOpenedPosition=isset($response['already_opened_position'])?true:false;
        
        
        return view('user.quizmanagement.quiz-bank', compact('quizbankmanagement', 'quizGroups','UserAccess','canAccess','alreadOpenedPosition'));
    }

    public function showQuiz(Request $request,$quiz_management_slug, $quiz_slug)
    {   
        $activated=$request->activate; 
        $query = $request->input('query');
        $quizbankmanagement = QuizBankManagement::where('slug', $quiz_management_slug)->active()->firstOrFail();
        $quiz = QuizBank::where('slug', $quiz_slug)->active()->firstOrFail();
        // Retrieve all quizzes in the same group as the current quiz
        $allquizzes = $this->searchQuizz($query,$quizbankmanagement->id,$quiz->quiz_group);    
        // dd($quiz->id,$allquizzes);
        // Find the index of the current quiz in the list of all quizzes
        $currentIndex = $allquizzes->search(function ($item) use ($quiz) {
            return $item->id == $quiz->id;
        });

        // Determine the previous and next quizzes, if they exist
        $previousQuiz = null;
        $nextQuiz = null;

        //*************************Next & Previous Quiz Links *************************//
            // Retrieve all quizzes under the current quiz management
            $quizzes = QuizBank::where('quizbankmanagement_id', $quizbankmanagement->id)
                               ->active()
                               ->orderBy('quiz_group')
                               ->orderBy('created_at', 'asc')
                               ->get();
            $quizGroups = $quizzes->groupBy('quiz_group');

            // Get the current group and index of the quiz
            $currentGroup = $quiz->quiz_group;
            $currentQIndex = $quizGroups[$currentGroup]->search(function ($item) use ($quiz) {
                return $item->id === $quiz->id;
            });
            // Get the next quiz
            $linksResult = $this->getNextPreviousQuiz($currentGroup, $currentQIndex, $quizGroups,);
            $previousQuiz = $linksResult['previousQuiz'];
            $nextQuiz = $linksResult['nextQuiz'];

            if($linksResult['quizGroupCompleted']==1){
                $this->quizCompleted($request,$quiz_management_slug,$quiz_slug,1);
            }

        //*************************Next & Previous Quiz Links Ends*************************//

        // if ($currentIndex > 0) {
        //     $previousQuiz = $allquizzes[$currentIndex - 1];
        // }

        // if ($currentIndex < $allquizzes->count() - 1) {
        //     $nextQuiz = $allquizzes[$currentIndex + 1];
        // }

        // Skip user access check if it is the first quiz of Group
        if ($currentIndex  != 0 || $activated ) {
            // Check user access
            $response=UserAccess::hasAccess(auth()->user(),'quizbank',$quizbankmanagement->id,$activated);

            if(!$response['access']){ 
                if(isset($response['already_opened_position'])){
                    Session::flash('already_opened_position',true);      
                }elseif(isset($response['can_access'])){
                    Session::flash('can_access',true);    
                }
                return redirect()->back()->with('error',$response['message']);
            }
        } 
        $this->markAsRead($quiz->id,$quizbankmanagement->id);
        
        //Mainly used for pdfbased
        $responsse=UserAccess::hasAccess(auth()->user(),'quizbank',$quizbankmanagement->id);
        $UserAccess=$responsse['access']?true:false;//If user has access to quizbank
        $canAccess=isset($responsse['can_access'])?true:false;
        //Mainly used for pdfbased ends

        // Check if the request is an AJAX request
        if ($request->ajax()) {
            // Return the quiz details partial
            return view('user.quizmanagement.partials.quiz-detail', [
                'quizbankmanagement' => $quizbankmanagement,
                'allquizzes'=>$allquizzes,
                'quiz' => $quiz,
                'previousQuiz' => $previousQuiz,
                'nextQuiz' => $nextQuiz,
                'query'=>$query,
                'UserAccess'=>$UserAccess,
                'canAccess'=>$canAccess,
            ]);
        } else {
            // Return the full quiz test page
            return view('user.quizmanagement.quiz-test', [
                'quizbankmanagement' => $quizbankmanagement,
                'allquizzes'=>$allquizzes,
                'quiz' => $quiz,
                'previousQuiz' => $previousQuiz,
                'nextQuiz' => $nextQuiz,
                'query'=>$query,
                'UserAccess'=>$UserAccess,
                'canAccess'=>$canAccess,
            ]);
        }
    }

    public static function markAsRead($quiz_id,$quizbankmanagement_id)
    {
        $progress = QuizProgress::where('user_id',auth()->user()->id)->where('quiz_id',$quiz_id)->first();
        if (!$progress) {
             QuizProgress::create([
                'user_id' => auth()->user()->id,
                'quizbankmanagement_id'=>$quizbankmanagement_id,
                'quiz_id' => $quiz_id,
                'is_read' => true,
            ]);
        } 
    }

    public function quizCompleted(Request $request, $quiz_management_slug, $quiz_slug,$infunction=null)
    {   

        // Retrieve the quiz and quiz management entities
        $quizbankmanagement = QuizBankManagement::where('slug', $quiz_management_slug)->active()->firstOrFail();
        $quiz = QuizBank::where('slug', $quiz_slug)->active()->firstOrFail();
        // Calculate the time spent on the quiz
        $timeSpent = $request->query('time_spent');
        // Store the quiz completion time and user id in the database
        $user_id = auth()->user()->id;
        // Use the updateOrCreate method to either update an existing record or create a new one
        QuizCompletion::updateOrCreate(
            [
                'quizbankmanagement_id' => $quizbankmanagement->id,
                'quiz_id' => $quiz->id,
                'user_id' => $user_id,
                'quiz_group' => $quiz->quiz_group
            ],
            [
                'time_spent' => $timeSpent
            ]
        );


        // Clear the time spent from local storage
        // if (isset($_COOKIE['timeSpent'])) {
        //     setcookie('timeSpent', '', time() - 3600);
        // }

        //Redirect the user to the quiz results page
        if(!$infunction){
            return redirect()->route('user.quiz.management.show', [
                'quiz_management_slug' => $quizbankmanagement->slug,
            ])->with('success',"SuccesFully Completed"); 
        }
        
    }


    public function searchQuizzes($quiz_management_slug,$quiz_group)
    {
        $query = request()->input('query');
        $quizbankmanagement = QuizBankManagement::where('slug', $quiz_management_slug)->active()->first();
        $allquizzes = $this->searchQuizz($query,$quizbankmanagement->id,$quiz_group);

        return view('user.quizmanagement.partials.quiz-buttons', [
            'allquizzes' => $allquizzes,
            'quiz' => $allquizzes->first(),
            'quizbankmanagement'=>$quizbankmanagement,
            'query'=> $query
        ])->render();
    }

    private function searchQuizz($keyword, $quiz_management_id, $quiz_group)
    {
        $allquizzes = QuizBank::where('quizbankmanagement_id', $quiz_management_id)
            ->when($keyword, function ($q) use ($keyword) {
                $q->where(function ($innerQ) use ($keyword) {
                    $innerQ->where('title', 'like', "%$keyword%")
                        ->orWhere('slug', 'like', "%$keyword%")
                        ->orWhere('details', 'like', "%$keyword%");
                });
            })
            ->when(empty($keyword), function ($q) use ($quiz_group) {
                $q->where('quiz_group', $quiz_group);
            })
            ->orderBy('created_at', 'asc')
            ->active()
            ->get();

        return $allquizzes;
    }



    public function showvideo($var = null)
    {
        return view('user.video');
    }

    public function savevideo(Request $request)
    {
        // Validate the video file here if needed
        Helpers::upload('user/recordings/', config('fileformats.video'), $request->file('video'));
        // Save the video path in your database here
        return response()->json(['success' => true]);
    }

    private function getNextPreviousQuiz($currentGroup, $currentIndex, $quizGroups) {
        $previousQuiz = null;
        $nextQuiz = null;
        $quizGroupCompleted = null;

        // Logic for previous quiz
        if ($currentIndex == 0) {// Check if the current index is the beginning of the current group
            $prevGroupKeys = $quizGroups->keys()->toArray();
            $currentGroupIndex = array_search($currentGroup, $prevGroupKeys);
            
            if ($currentGroupIndex != 0) {
                $prevGroup = $prevGroupKeys[$currentGroupIndex - 1];
                $previousQuiz = $quizGroups[$prevGroup][count($quizGroups[$prevGroup]) - 1];
            }
        } else {
            // If it's not the beginning of the group, get the directly preceding quiz as the previous quiz
            $previousQuiz = $quizGroups[$currentGroup][$currentIndex - 1];
        }

        // Logic for next quiz
        if ($currentIndex == ($quizGroups[$currentGroup]->count() - 1)) {
            $nextGroupKeys = $quizGroups->keys()->toArray();
            $currentGroupIndex = array_search($currentGroup, $nextGroupKeys);
            
            // This is where you call the completion function
            $quizGroupCompleted=1;
            

            if ($currentGroupIndex != (count($nextGroupKeys) - 1)) {
                $nextGroup = $nextGroupKeys[$currentGroupIndex + 1];
                $nextQuiz = $quizGroups[$nextGroup][0];
            }
        } else {
            $nextQuiz = $quizGroups[$currentGroup][$currentIndex + 1];
        }

        return [
            'previousQuiz' => $previousQuiz,
            'nextQuiz' => $nextQuiz,
            'quizGroupCompleted' => $quizGroupCompleted
        ];
    }

}
