namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class InvitePoliceController extends Controller
{
    public function show(Request $request, $token)
    {
        if (!URL::hasValidSignature($request)) {
            return response()->view('errors.link-expired', [], 403);
        }

        return view('invite-police');
    }

    public static function generateSecureLink()
    {
        return URL::temporarySignedRoute(
            'invite.police.show',
            now()->addMinutes(90),
            ['token' => uniqid()]
        );
    }
}

