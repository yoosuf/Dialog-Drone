<?php

namespace App\Http\Controllers\API;
use App\Match;
use App\MatchReward;
use App\Reward;
use App\UserReward;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\ApiController;
use Carbon\Carbon;
use ValidationException;
use Input;
use DB;

class RewardController extends ApiController
{
    public   function getRewardsForMatch($matchID)
    {
     //   dd(Carbon::now());
        if(MatchReward::where('match_id',$matchID)){
            //Check the reward still valid comparing time
            $matchReward= DB::table('match_rewards')
                ->select('no_of_rewards','id','reward_id')
                ->where('start','<',Carbon::now())
                ->where('end','>',Carbon::now())
                ->where('match_id',$matchID)
                ->whereNotIn('reward_id', function ($query)
                {
                    $query->select('match_reward_id')
                        ->from('user_rewards')
                        ->whereRaw('user_rewards.user_id ='.$this->user->id.'');
                });

            if($matchReward->first() && $matchReward->first()->no_of_rewards>UserReward::where('match_reward_id',$matchReward->first()->id)->count())
             {

                 $rewardCount=UserReward::select('user_id','id','reward_id')
                             ->where('user_id',$this->user->id)
                             ->where('match_reward_id',$matchReward->first()->reward_id)
                             ->count();
                  if($rewardCount<1) {
                      $couponID=$matchReward->first()->reward_id;
                       $userReward = new UserReward;
                       $userReward->match_reward_id = $couponID;
                       $userReward->user_id = $this->user->id;
                       $userReward->status = 'pending';
                        if ($userReward->save()) {
                               $rewards = Reward::where('id', $couponID)->first();
                               $response['data'] = [
                                   'coupon_id' => $couponID,
                                   'title' => $rewards->title,
                                   'user_id' => $this->user->id,
                                   'status' => 'pending',
                                   'description' => $rewards->description,
                                   'image' => asset('/') . $rewards->image
                               ];
                       return \Response::json($response);
                        }
                    }
                 throw new ValidationException(['This Reward can active one time only.']);
             }
            throw new ValidationException(['Rewards Not Available.']);
        }
    }

    public function redeemReward()
    {
        $matchID=input::get('match_id');
        $couponID=input::get('coupon_id');
        $redeemCode=input::get('redeem_code');
        Match::findOrFail($matchID);
        if(MatchReward::find($couponID)&& MatchReward::select('counter_pin')->where('counter_pin',$redeemCode)->first()){
            //Check the reward still valid by checking expire date
            $matchReward=MatchReward::select('expire')
                ->where('expire','>',Carbon::now());

            if($matchReward->first())
            {
                $redeemed=UserReward::where('user_id',$this->user->id)
                                    ->where('match_reward_id',$couponID)->first();
                $redeemed->status='redeemed';
                if($redeemed->save())
                {
                    return \Response::json(array('success' => true));
                }

            }
            throw new ValidationException(['Expired']);
        }
        throw new ValidationException(['Redeem code invalid']);
    }

    public function getCouponList()
    {
        $userRewards=DB::table('user_rewards AS usr_rew')
            ->join('rewards AS rew', 'usr_rew.match_reward_id', '=', 'rew.id')
            ->where('usr_rew.user_id',$this->user->id)
            ->select(
                'usr_rew.user_id','usr_rew.status',
                'rew.id','rew.title','rew.description','rew.image'
            )
            ->get();

        $response = [];
        if($userRewards) {
            foreach ($userRewards as $userReward) {
                $response[] = [
                    'coupon_id' => $userReward->id,
                    'user_id' => $this->user->id,
                    'title' => $userReward->title,
                    'description' => $userReward->description,
                    'status' => $userReward->status,
                    'image' => asset('/') . $userReward->image

                ];
            }
            $returnArg['data'] = $response;
            return \Response::json($returnArg);
        }
        throw new ValidationException(['No Rewards found']);
    }
}