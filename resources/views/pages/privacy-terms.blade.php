@extends('layouts.app')

@section('title', 'Privasi & Syarat Ketentuan - Kumala Property')

@section('content')
<div class="container" style="padding-top: 24px; padding-bottom: 80px;">

    <!-- Breadcrumb Bar -->
    <div class="breadcrumbs-bar" style="padding: 0; margin-bottom: 24px;">
        <ul class="breadcrumbs-list">
            <li class="breadcrumbs-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumbs-separator">&gt;</li>
            <li class="breadcrumbs-item active">Privasi &amp; Syarat Ketentuan</li>
        </ul>
    </div>

    <!-- Header Section -->
    <div style="margin-bottom: 40px; border-bottom: 1px solid var(--border-color); padding-bottom: 24px;">
        <h1 style="font-size: clamp(1.8rem, 3vw, 2.4rem); color: var(--primary-navy); margin-bottom: 10px; font-weight: 800;">
            Privasi &amp; Syarat Ketentuan
        </h1>
        <p style="color: var(--text-muted); font-size: 0.95rem; margin: 0; max-width: 720px; line-height: 1.5;">
            Informasi mengenai kebijakan privasi, tata kelola penggunaan layanan, dan ketentuan operasional platform.
        </p>
    </div>

    <!-- Content Sections Container -->
    <div style="display: flex; flex-direction: column; gap: 24px; max-width: 800px;">

        <!-- 1. Kebijakan Privasi -->
        <section style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; padding: 24px;">
            <h2 style="font-size: 1.2rem; color: var(--primary-navy); margin-top: 0; margin-bottom: 12px; font-weight: 700;">
                Kebijakan Privasi
            </h2>
            <p style="color: #64748B; font-size: 0.92rem; line-height: 1.6; margin: 0;">
                [Teks resmi menunggu dari pihak Kumala Property]
            </p>
        </section>

        <!-- 2. Ketentuan Layanan -->
        <section style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; padding: 24px;">
            <h2 style="font-size: 1.2rem; color: var(--primary-navy); margin-top: 0; margin-bottom: 12px; font-weight: 700;">
                Ketentuan Layanan
            </h2>
            <p style="color: #64748B; font-size: 0.92rem; line-height: 1.6; margin: 0;">
                [Teks resmi menunggu dari pihak Kumala Property]
            </p>
        </section>

        <!-- 3. Ketentuan Konsinyasi dan Kurasi Listing -->
        <section style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; padding: 24px;">
            <h2 style="font-size: 1.2rem; color: var(--primary-navy); margin-top: 0; margin-bottom: 12px; font-weight: 700;">
                Ketentuan Konsinyasi dan Kurasi Listing
            </h2>
            <p style="color: #64748B; font-size: 0.92rem; line-height: 1.6; margin: 0;">
                [Teks resmi menunggu dari pihak Kumala Property]
            </p>
        </section>

        <!-- 4. Kontak -->
        <section style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 12px; padding: 24px;">
            <h2 style="font-size: 1.2rem; color: var(--primary-navy); margin-top: 0; margin-bottom: 12px; font-weight: 700;">
                Kontak
            </h2>
            <p style="color: #64748B; font-size: 0.92rem; line-height: 1.6; margin: 0 0 16px 0;">
                [Teks resmi menunggu dari pihak Kumala Property]
            </p>

            @php
                $contactEmail = !empty($globalSettings['email_official']) ? $globalSettings['email_official'] : (!empty($globalSettings['email_cs']) ? $globalSettings['email_cs'] : null);
                $hasContactInfo = !empty($globalSettings['hotline_wa']) || !empty($contactEmail) || !empty($globalSettings['address']);
            @endphp

            @if($hasContactInfo)
            <div style="display: flex; flex-direction: column; gap: 8px; font-size: 0.88rem; color: #475569; padding-top: 14px; border-top: 1px solid var(--border-light, #F1F5F9);">
                @if(!empty($globalSettings['hotline_wa']))
                <div>
                    <strong>Hotline WhatsApp:</strong> {{ $globalSettings['hotline_wa'] }}
                </div>
                @endif
                @if(!empty($contactEmail))
                <div>
                    <strong>Email:</strong> {{ $contactEmail }}
                </div>
                @endif
                @if(!empty($globalSettings['address']))
                <div>
                    <strong>Alamat:</strong> {{ $globalSettings['address'] }}
                </div>
                @endif
            </div>
            @endif
        </section>

    </div>

</div>
@endsection

