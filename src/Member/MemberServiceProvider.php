<?php
namespace AllianceTools\Member;

use Illuminate\Support\ServiceProvider;
use AllianceTools\Member\Repository\EloquentMemberRepository;
use AllianceTools\Member\Repository\MemberRepositoryInterface;

class MemberServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(MemberInterface::class, EloquentMember::class);
        $this->app->bind(MemberRepositoryInterface::class, EloquentMemberRepository::class);
    }
}