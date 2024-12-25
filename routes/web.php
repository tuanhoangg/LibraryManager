<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Api\AuthorApiController;
use App\Http\Controllers\Api\BookApiController;
use App\Http\Controllers\Api\BookItemApiController;
use App\Http\Controllers\Api\BookLanguageApiController;
use App\Http\Controllers\Api\BorrowHistoryApiController;
use App\Http\Controllers\Api\FinesApiController;
use App\Http\Controllers\Api\GenresApiController;
use App\Http\Controllers\Api\LateReturnApiController;
use App\Http\Controllers\Api\MemberApiController;
use App\Http\Controllers\Api\MembershipPlanApiController;
use App\Http\Controllers\Api\PubliserApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookLanguageController;
use App\Http\Controllers\BorrowHistoryController;
use App\Http\Controllers\FinesController;
use App\Http\Controllers\GenresController;
use App\Http\Controllers\LateReturnController;
use App\Http\Controllers\MembershipPlanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PubliserController;
use App\Http\Controllers\SendMailController;
use App\Http\Controllers\UserController;
use App\Models\Roles;
use App\Services\LateReturnService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        if (auth()->user()->role_id == Roles::ROLE_USER) {
            return redirect()->route('book.search');
        }
        return redirect()->route('dashboard');
    });
    Route::middleware(['role:' . Roles::ROLE_ADMIN])->get('/dashboard', [DashboardController::class, 'dashboardView'])->name('dashboard');

    //Profile manager
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('update.profile');


    //User mananger
    Route::prefix('users')->group(function () {
        Route::middleware(['role:' . Roles::ROLE_ADMIN])->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('user.list');
            Route::get('/create', [UserController::class, 'addUserView'])->name('user.add.view');
            Route::post('/', [UserController::class, 'addUser'])->name('user.store');
            Route::get('/{id}/edit', [UserController::class, 'editUserView'])->name('user.edit');
            Route::post('/post/{id}', [UserController::class, 'edit'])->name('user.update');
            Route::get('/{id}', [UserController::class, 'show'])->name('user.show');
            Route::delete('/delete/{id}', [UserApiController::class, 'deleteUser'])->name('user.destroy');
            Route::post('re-send/verify',  [UserApiController::class, 'resendEmail']);
            Route::post('/import-users', [UserController::class, 'import'])->name('users.import');
            Route::get('/import-status/{jobId}', function ($jobId) {
                $status = Cache::get('import_status_' . $jobId, [
                    'status' => 'pending',
                    'message' => 'Job is still processing'
                ]);

                return response()->json($status);
            });

            //Api
            Route::get('/api/get-users', [UserApiController::class, 'getUserList']);
        });

        //Api
        Route::post('/api/change-password', [UserApiController::class, 'changePassword']);
    });

    //Genres manager

    Route::prefix('genres')->middleware(['role:' . Roles::ROLE_ADMIN . '|' . Roles::ROLE_LIBRARIAN])->group(function () {
        Route::get('/', [GenresController::class, 'index'])->name('genres.list');

        // api
        Route::get('/api/get-genres', [GenresApiController::class, 'getGenresList']);
        Route::post('/api/add-genres', [GenresApiController::class, 'createGenres']);
        Route::post('/api/update-genres', [GenresApiController::class, 'updateGenres']);
        Route::delete('/api/delete-genres/{id}', [GenresApiController::class, 'deleteGenres']);
    });

    //Author manager
    Route::prefix('author')->middleware(['role:' . Roles::ROLE_ADMIN . '|' . Roles::ROLE_LIBRARIAN])->group(function () {
        Route::get('/', [AuthorController::class, 'index'])->name('author.list');

        Route::get('/api/get-author', [AuthorApiController::class, 'getAuthorList'])->name('api.get.authors');

        Route::post('/api/add-author', [AuthorApiController::class, 'createAuthor']);
        Route::post('/api/update-author', [AuthorApiController::class, 'updateAuthor']);
        Route::delete('/api/delete-author/{id}', [AuthorApiController::class, 'deleteAuthor']);
    });

    //Publiser manager
    Route::prefix('publisers')->middleware(['role:' . Roles::ROLE_ADMIN . '|' . Roles::ROLE_LIBRARIAN])->group(function () {
        Route::get('/', [PubliserController::class, 'index'])->name('publiser.list');

        Route::get('/api/get-publiser', [PubliserApiController::class, 'getPubliserList']);

        Route::post('/api/add-publiser', [PubliserApiController::class, 'createPubliser']);
        Route::post('/api/update-publiser', [PubliserApiController::class, 'updatePubliser']);
        Route::delete('/api/delete-publiser/{id}', [PubliserApiController::class, 'deletePubliser']);
    });

    //Book language manager
    Route::prefix('book-language')->middleware(['role:' . Roles::ROLE_ADMIN . '|' . Roles::ROLE_LIBRARIAN])->group(function () {
        Route::get('/', [BookLanguageController::class, 'index'])->name('book.language.list');

        //api
        Route::get('/api/get-book-language', [BookLanguageApiController::class, 'getBookLanguageList']);

        Route::post('/api/add-book-language', [BookLanguageApiController::class, 'createBookLanguage']);
        Route::post('/api/update-book-language', [BookLanguageApiController::class, 'updateBookLanguage']);
        Route::delete('/api/delete-book-language/{id}', [BookLanguageApiController::class, 'deleteBookLanguage']);
    });


    //Book  manager
    Route::prefix('books')->group(function () {
        Route::middleware(['role:' . Roles::ROLE_ADMIN . '|' . Roles::ROLE_LIBRARIAN])->group(function () {
            Route::get('/', [BookController::class, 'index'])->name('book.list');
            Route::get('/create', [BookController::class, 'createBook'])->name('book.create');
            Route::get('/{id}/edit', [BookController::class, 'editBook'])->name('book.edit');
            Route::post('/import', [BookController::class, 'import']);
            Route::get('/import-status/{jobId}', [BookController::class, 'importStatus']);

            Route::post('/api/add-book', [BookApiController::class, 'storeBook']);
            Route::post('/api/update-book', [BookApiController::class, 'updateBook']);
            Route::delete('/api/delete-book/{id}', [BookApiController::class, 'deleteBook']);
        });
        // API routes
        Route::get('/api/get-books', [BookApiController::class, 'getBookList'])->name('api.get.books');
        Route::get('/api/get-book', [BookApiController::class, 'getBook']);
        Route::get('/api/barcode-pdf/{isbn_code}', [BookApiController::class, 'generateBarcodesPDF'])->name('books.barcodes');


        Route::get('/search-book', [BookController::class, 'searchBook'])->name('book.search')->middleware('role:' . Roles::ROLE_USER);
    });
    //Book  item manager
    Route::prefix('book-item')->middleware(['role:' . Roles::ROLE_ADMIN . '|' . Roles::ROLE_LIBRARIAN])->group(function () {
        Route::get('/', [BookItemApiController::class, 'getBookItems']);
        Route::get('/api/get-by-isbn', [BookItemApiController::class, 'getBookItemsByIsbn']);
    });

    //Membership plans
    Route::prefix('membership-plans')->group(function () {

        Route::middleware(['role:' . Roles::ROLE_ADMIN])->group(function () {
            Route::get('/', [MembershipPlanController::class, 'index'])->name('membership.plan.list');

            /// api
            Route::get('/api/get-membership-plan', [MembershipPlanApiController::class, 'getMembershipPlanList']);

            Route::post('/api/add-membership-plan', [MembershipPlanApiController::class, 'createMembershipPlan']);
            Route::post('/api/update-membership-plan', [MembershipPlanApiController::class, 'updateMembershipPlan']);
            Route::delete('/api/delete-membership-plan/{id}', [MembershipPlanApiController::class, 'deleteMembershipPlan']);
        });
        Route::get('/plan-list', [MembershipPlanController::class, 'membershipPlanOption'])->name('membership.select.plan');
        Route::post('/register-membership-plan', [MembershipPlanController::class, 'membershipRegister'])->name('membership.plan.register');
    });

    //Borrow-history
    Route::prefix('borrow-histories')->group(function () {
        Route::post('/borrow', [BorrowHistoryApiController::class, 'borrowBook']);
        Route::get('/', [BorrowHistoryController::class, 'index'])->name('borrow.history.list');
        Route::get('/api/history', [BorrowHistoryApiController::class, 'getBorrowHistoryList'])->name('borrow.history');
        Route::post('/api/cancel-status', [BorrowHistoryApiController::class, 'cancelBorrowStatus']);
        Route::middleware(['role:' . Roles::ROLE_ADMIN . '|' . Roles::ROLE_LIBRARIAN])->group(function () {
            Route::post('/api/update-status', [BorrowHistoryApiController::class, 'updateBorrowStatus']);
            Route::post('/api/send-email-ovedue', [BorrowHistoryApiController::class, 'sendMailLateReturns']);
        });
    });


    // Late return 
    Route::prefix('late-returns')->group(function () {
        Route::get('/', [LateReturnController::class, 'index'])->name('late.return.list');

        // api
        Route::get('/api/get-late-return-list', [LateReturnApiController::class, 'getlateReturnList']);
        Route::post('/api/payment', [LateReturnApiController::class, 'lateReturnPaymentByMomo']);
    });

    // Fines
    Route::prefix('fines')->group(function () {
        Route::get('/', [FinesController::class, 'index'])->name('fines.list');

        // api
        Route::get('/api/get-fines-list', [FinesApiController::class, 'getFinesList']);
        Route::post('/api/payment', [FinesApiController::class, 'lateReturnPaymentByMomo']);
    });


    // Member
    Route::prefix('members')->group(function () {
        Route::get('/api/get-member-list', [MemberApiController::class, 'getMemberList']);
        Route::get('/api/get-member', [MemberApiController::class, 'getMemberById']);
    });

    // Send Mail
    Route::prefix('send-mail')->group(function () {
        Route::post('/late-return', [SendMailController::class, 'sendMailLateReturn']);
        Route::post('/fines', [SendMailController::class, 'sendMailFines']);
    });

    // Batchs
    Route::prefix('batches')->group(function () {
        Route::get('', function () {
            return view('pages.batchs.index');
        })->name('batches.index');
    });
});



Route::get('/login', function () {
    if (auth()->user()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('login.view');

Route::get('/admin/login', function () {
    if (auth()->user()) {
        return redirect()->route('book.search');
    }
    return view('auth.admin-login');
});

Route::get('/librarian/login', function () {
    if (auth()->user()) {
        return redirect()->route('book.list');
    }
    return view('auth.librarian-login');
});


Route::get('/register', function () {
    return view('auth.register');
})->name('register.view');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');
Route::get('/resend-verify-email', [AuthController::class, 'resendEmailView'])->name('verification.notice');
Route::post('/resend-verify', [AuthController::class, 'resendVerifyEmail'])->name('resend.verify.email');

Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendEmailForgotPassword'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'updateResetPassword'])->middleware('guest')->name('password.update');

Route::post('/payment-momo', [PaymentController::class, 'momoPayment'])->name('create.momo.payment');
Route::post('/payment-vnpay', [PaymentController::class, 'vnpayPayment']);
Route::post('/return-vnpay', [PaymentController::class, 'return']);
Route::get('return-payment', [PaymentController::class, 'returnPayment'])->name('return-payment');
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::get('/payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment/failure', [PaymentController::class, 'paymentFailure'])->name('payment.failure');

Route::get('/books/export', [BookController::class, 'export'])->name('books.export');
Route::get('/export/users', [UserController::class, 'export']);
Route::get('/export/borrow', [BorrowHistoryController::class, 'export'])->name('borrow.export');

//batchs
Route::post('/update-late-return', [BatchController::class, 'batchUpdateLateReturn'])->name('update-late-return');
Route::post('/update-member', [BatchController::class, 'batchMember'])->name('update-member');
