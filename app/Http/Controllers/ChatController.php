<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetChatRequest;
use App\Http\Requests\StoreChatRequest;
use App\Models\ChatModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetChatRequest $request):JsonResponse
    {
        $data = $request->validated();
        $isPrivate = 1;
        if($request->has('is_private')){
            $isPrivate=(int)$data['is_private'];
        }

        $chats = ChatModel::where('is_private',$isPrivate)
            ->hasParticipant(auth()->user()->id)
            ->whereHas('messages')
            ->with('lastMessage.user','participant.user')
            ->lastest('update_at')
            ->get();
        return $this->success($chats);
    }

    public function store(StoreChatRequest $request)
    {
        $data = $this->prepareStoreData($request);
        if($data['user_id']===$data['otherUserId']){
            return $this->error('You can not create a chat with your own');
        }
    }

    private function prepareStoreData(StoreChatRequest $request):array{
        $data=$request->validated();
        $otherUserId = (int)$data['user_id'];
        unset($data['user_id']);
        $data['create_by']=auth()->user()->id;
        return [
            'otherUserId'=>$otherUserId,
            'userId'=>auth()->user()->id,
            'data'=>$data
        ];
    }

    public function show(ChatModel $chat):JsonResponse
    {
        $chat->load('lastMessage.user','participant.user');
        return $this->success($chat);
    }
}
