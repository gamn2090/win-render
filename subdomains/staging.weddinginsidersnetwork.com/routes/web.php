<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\BookingFlowController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\RegisteredVendorController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\EventController;
use App\Models\Event;
use App\Models\Vendor;
use Illuminate\Http\Request;

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


Route::get('/', function () {
    if (Auth::guard('vendor')->check()) {
        return redirect('/vendor/dashboard');
    } else if (Auth::guard('web')->check()) {
        return redirect('/dashboard');
    } else {
        return view('welcome');
    }
})->name('welcome');

/**
 * DEVELOPMENT ROUTES
 */
Route::get('/new', function () {
    return view('welcome-new');
});
//Route::get('/preregister', [VendorController::class, 'preregister']);

//Route::get('/vendor/dashboard/test', [VendorController::class, 'vendorDashboardTest'])->middleware('auth:vendor')->name('vendor.dashboard.test');

/**
 * END DEV ROUTES
 */

Route::get('/contact', function () {
    return view('contact');
});


Route::get('/join-us', function () {
    return view('contact');
});

Route::get('/for-couples', function () {
    return view('for-couples');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/terms-of-service', function () {
    return view('policy.tos');
});

Route::get('/blog', [BlogController::class, 'index']);

Route::get('/blog/post/{id}', [BlogController::class, 'viewPost']);

Route::get('/dashboard', [RegisteredUserController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/vendor/dashboard', [VendorController::class, 'vendorDashboard'])
    ->middleware('auth:vendor')->name('vendor.dashboard');

Route::get('ref/user/{id}', [RegisteredUserController::class, 'registerReferral'])
    ->name('register.referral');

Route::post('ref/user/register', [RegisteredUserController::class, 'finishReferralSetup'])
    ->name('register.referral.finish');

Route::post('user/upload/image', [RegisteredUserController::class, 'uploadProfileImage'])
    ->name('user.upload.image');

Route::get('/vendor/profile/{id}', [ProfileController::class, 'vendor'])->name('profile.vendor');
Route::get('/client/profile/{id}', [ProfileController::class, 'client'])->name('profile.client');

Route::middleware('auth:vendor')->group(function () {
    //Route::get('/hs/tst', [VendorController::class, 'hsTest']);

    Route::post('/vendor/remove/client', [VendorController::class, 'removePairing'])->name('vendor.remove.pairing');
    Route::get('/vendor/client/list', [VendorController::class, 'clientList']);
    Route::get('/vendor/list', [VendorController::class, 'vendorList']);
    Route::get('/vendor/google/auth', [VendorController::class, 'googleAuth']);
    Route::get('/vendor/google/auth/callback', [VendorController::class, 'googleCallback']);
    Route::get('/billing-portal', function (Request $request) {
        return $request->user()->redirectToBillingPortal(route('vendor.dashboard'));
    });
    Route::get('/vendor/insights', [VendorController::class, 'insights'])->name('vendor.insights');
    Route::get('/vendor/search/vendors', [VendorController::class, 'searchVendorType'])->name('search.vendors');
    Route::get('/vendor/search/vendors/{type}', [VendorController::class, 'searchVendorType'])->name('search.vendor.type');
    Route::get('/vendor/profile', [ProfileController::class, 'vendorEdit'])->name('profile.vendoredit');
    Route::get('/vendor/inquiries', [VendorController::class, 'getInquiries'])->name('vendor.inquiries');
    Route::get('/vendor/pay', [VendorController::class, 'attemptPayment'])->name('vendor.pay');
    Route::get('/vendor/subscription/confirm/{session_id}', [VendorController::class, 'confirmSubscription'])->name('vendor.subscription.confirm');
    Route::get('/vendor/couples', [VendorController::class, 'findCouples'])->name('vendor.find.couples');
    Route::get('/vendor/booked', [VendorController::class, 'booked'])->name('vendor.booked');
    Route::get('/vendor/archive', [VendorController::class, 'archive'])->name('vendor.archive');
    Route::get('/vendor/archive/{id}/{ven_id}', [VendorController::class, 'archiveClient'])->name('vendor.archive.client');

    Route::get('/vendor/inbox', [VendorController::class, 'inbox'])->name('vendor.inbox');
    Route::get('/vendor/message/{id}', [VendorController::class, 'message'])->name('vendor.message');
    Route::get('/vendor/chat/{id}', [VendorController::class, 'showVendorChat'])->name('vendor.chat.vendor');
    Route::post('/vendor/message/client', [ChatController::class, 'messageClient'])->name('vendor.message.client');
    Route::get('/vendor/messages/{convoID}', [VendorController::class, 'getMessages'])->name('vendor.get.messages');
    Route::get('/vendor/messages/{userType}/{otherID}', [VendorController::class, 'getMessagesFromID'])->name('vendor.messages.fromid');
    Route::post('/vendor/send/message', [VendorController::class, 'sendMessage'])->name('vendor.send.message');

    Route::get('/vendor/create/client', function () {
        return view('vendor_create_client', ['page' => 'client_invite']);
    })->name('vendor.create.client');
    Route::get('/vendor/create/vendors', function () {
        return view('vendor_create_vendors', ['page' => 'vendor_invite']);
    })->name('vendor.create.vendors');
    Route::post('/vendor/create/client', [VendorController::class, 'createClientInvite'])->name('create.client.invite');
    Route::post('/vendor/create/vendor', [VendorController::class, 'createVendorInvite'])->name('create.vendor.invite');
    Route::post('vendor/upload/image', [RegisteredVendorController::class, 'uploadProfileImage'])
        ->name('vendor.upload.image');
    Route::post('vendor/upload/portfolio', [ProfileController::class, 'uploadPortfolioImage'])
        ->name('vendor.upload.portfolio');
    Route::post('vendor/remove/portfolio', [ProfileController::class, 'deletePortfolioImage'])
        ->name('vendor.remove.portfolio');
    Route::post('/vendor/request/connection', [VendorController::class, 'createConnectionRequest'])->name('create.connect.request');
    Route::post('/vendor/pairing/answer', [VendorController::class, 'answerPairingRequest'])->name('pairing.request.answer');

    Route::post('/vendor/connection/answer', [VendorController::class, 'answerConnectionRequest'])->name('connect.request.answer');
    Route::post('/vendor/logout', [AuthenticatedSessionController::class, 'destroyVendor'])->name('logout.vendor');
    //profile
    Route::patch('/vendor/profile', [ProfileController::class, 'updateVendor'])->name('vendor.profile.update');
    Route::post('/vendor/googlePlace', [ProfileController::class, 'updateVendorGooglePlace'])->name('vendor.update.google');
    Route::post('/vendor/business/search', [ProfileController::class, 'findGooglePlace'])->name('vendor.business.search');
    Route::post('/vendor/business/link', [ProfileController::class, 'linkGooglePlace'])->name('vendor.business.link');
    Route::post('/vendor/business/unlink', [ProfileController::class, 'unlinkGooglePlace'])->name('vendor.business.unlink');
    //Route::get('/profiles/all', [ProfileController::class, 'viewAllProfiles'])->name('vendor.profile.all');
    //chat
    Route::get('/inbox/conversation/{id}', [ChatController::class, 'vendorViewConversation'])->name('get.vendor.conversation');
    Route::post('/meeting/answer', [BookingFlowController::class, 'answerMeetingRequest'])->name('meeting.answer');

    //endorse
    Route::post('/vendor/endorse', [VendorController::class, 'endorse'])->name('vendor.endorse');

    //events
    Route::post('/vendor/event/join', [EventController::class, 'joinEventWithCode'])->name('vendor.event.join');
});


Route::middleware('auth:web')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/edit', [ProfileController::class, 'userEdit'])->name('user.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //Route::post('/user/book', [UserController::class, 'createConnection'])->name('create.booking');
    Route::post('/toggle/favorite', [UserController::class, 'toggleFavoriteVendor'])->name('toggle.favorite');
    Route::post('/client/remove/vendor', [UserController::class, 'removePairing'])->name('remove.pairing');
    //search vendors
    Route::get('/search/vendors/{type}', [UserController::class, 'searchVendorType'])->name('search.vendor.type');
    //inbox 
    Route::get('/client/inbox', [UserController::class, 'inbox'])->name('client.inbox');
    Route::get('/client/message/{id}', [UserController::class, 'message'])->name('user.vendor.message');
    Route::get('/client/conversation/{id}', [ChatController::class, 'clientViewConversation'])->name('get.client.conversation');
    Route::get('/client/messages/{convoID}', [UserController::class, 'getMessages'])->name('client.get.messages');
    Route::post('/client/send/message', [UserController::class, 'sendMessage'])->name('user.send.message');
    //meetings
    Route::post('/client/meeting/request', [BookingFlowController::class, 'requestMeeting'])->name('user.request.meeting');
    Route::post('/client/send/inquiry', [BookingFlowController::class, 'clientSendInquiry'])->name('user.send.inquiry');
    Route::post('/client/book/vendor', [BookingFlowController::class, 'markVendorBooked'])->name('user.book.vendor');
    //lists
    Route::get('/vendors/list', [UserController::class, 'vendorList']);
});


//TODO: auth verification
Route::get('/message/vendor/{vendor_uuid}', [ChatController::class, 'messageVendor'])->name('chat.with.vendor');

Route::group(['middleware' => ['auth:web,vendor']], function() {
    Route::get('/appointments', [MeetingController::class, 'appointmentListPage'])->name('appointments.list');
    Route::get('/search/vendors', [VendorController::class, 'searchVendorType'])->name('search.vendors');
});

require __DIR__ . '/auth.php';
