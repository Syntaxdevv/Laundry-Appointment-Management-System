@extends('layouts.admin')

@section('title', 'Staff Details | LinisLaba')

@section('content')
<div class="min-h-screen bg-[#F3F5F2] p-[30px] font-['DM_Sans'] text-[#252A34] max-[600px]:px-[14px] max-[600px]:py-[20px]">
    <div class="mx-auto max-w-[1050px]">

        <a href="{{ route('admin.staff.index') }}"
           class="mb-[22px] inline-flex items-center gap-2 text-[12px] font-bold text-[#008F68] no-underline">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Staff Accounts
        </a>

        <div class="mb-[22px]">
            <h1 class="mb-[5px] text-[24px] font-extrabold">Staff Account Details</h1>
            <p class="m-0 text-[12px] text-[#849088]">View account information and registration details.</p>
        </div>

        <div class="mb-[22px] flex items-center gap-[18px] rounded-[11px] bg-[#252A34] p-[28px] text-white max-[600px]:flex-col max-[600px]:items-start">
            <div class="grid h-[65px] w-[65px] shrink-0 place-items-center rounded-[12px] bg-[#34423E] text-[25px] font-extrabold text-[#35C99A]">
                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            </div>

            <div>
                <small class="text-[11px] text-[#AEBBB5]">USER ACCOUNT #{{ $user->id }}</small>
                <h2 class="my-[5px] text-[22px] font-extrabold">{{ $user->name }}</h2>
                <p class="m-0 text-[12px] text-[#AEBBB5]">{{ $user->email }}</p>
            </div>
        </div>

        <section class="mb-5 rounded-[11px] border border-[#E5EAE6] bg-white p-6">
            <h3 class="mb-5 flex items-center gap-[10px] text-[14px] font-extrabold">
                <i class="fa-solid fa-user text-[#00A878]"></i>
                Account Information
            </h3>

            <div class="grid grid-cols-2 gap-[14px] max-[600px]:grid-cols-1">
                <div class="min-w-0 rounded-lg border border-[#E8EDE9] bg-[#F8FAF8] p-4">
                    <div class="mb-[7px] text-[10px] uppercase text-[#87938B]">Full Name</div>
                    <div class="break-words text-[13px] font-extrabold text-[#303A34]">
                        {{ $user->name }}
                    </div>
                </div>

                <div class="min-w-0 rounded-lg border border-[#E8EDE9] bg-[#F8FAF8] p-4">
                    <div class="mb-[7px] text-[10px] uppercase text-[#87938B]">Email Address</div>
                    <div class="break-words text-[13px] font-extrabold text-[#303A34]">
                        {{ $user->email }}
                    </div>
                </div>

                <div class="min-w-0 rounded-lg border border-[#E8EDE9] bg-[#F8FAF8] p-4">
                    <div class="mb-[7px] text-[10px] uppercase text-[#87938B]">Account ID</div>
                    <div class="break-words text-[13px] font-extrabold text-[#303A34]">
                        #{{ $user->id }}
                    </div>
                </div>

                <div class="min-w-0 rounded-lg border border-[#E8EDE9] bg-[#F8FAF8] p-4">
                    <div class="mb-[7px] text-[10px] uppercase text-[#87938B]">Registered On</div>
                    <div class="break-words text-[13px] font-extrabold text-[#303A34]">
                        {{ $user->created_at?->format('F d, Y') ?? '—' }}
                    </div>
                </div>

                <div class="min-w-0 rounded-lg border border-[#E8EDE9] bg-[#F8FAF8] p-4">
                    <div class="mb-[7px] text-[10px] uppercase text-[#87938B]">Last Updated</div>
                    <div class="break-words text-[13px] font-extrabold text-[#303A34]">
                        {{ $user->updated_at?->format('F d, Y') ?? '—' }}
                    </div>
                </div>

                <div class="min-w-0 rounded-lg border border-[#E8EDE9] bg-[#F8FAF8] p-4">
                    <div class="mb-[7px] text-[10px] uppercase text-[#87938B]">Account Email</div>
                    <div class="break-words text-[13px] font-extrabold text-[#303A34]">
                        {{ $user->email }}
                    </div>
                </div>
            </div>
        </section>

        <div class="mt-5 flex gap-[10px] max-[600px]:flex-col">
            <a href="{{ route('admin.staff.index') }}"
               class="inline-flex items-center justify-center gap-2 rounded-[7px] border border-[#DCE4DE] bg-white px-[18px] py-3 text-[11px] font-extrabold text-[#34483D] no-underline hover:bg-[#F1F5F2] max-[600px]:w-full">
                <i class="fa-solid fa-list"></i>
                Back to Staff List
            </a>
        </div>

    </div>
</div>
@endsection
