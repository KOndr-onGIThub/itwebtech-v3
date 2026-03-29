@extends('layout')

@section('title', 'Stránka dočasně nedostupná – 503')
@section('meta_description', 'Stránka je dočasně nedostupná z důvodu údržby.')

@section('content')
<section class="section bg-mx-dark min-h-[60vh] flex items-center">
  <div class="page-container max-w-xl text-center">
    <div class="text-8xl font-bold text-mx-orange mb-4">503</div>
    <h1 class="text-2xl font-semibold text-mx-white mb-4">Stránka dočasně nedostupná</h1>
    <p class="text-gray-400 text-sm leading-relaxed mb-8">
      Probíhá údržba webu. Omlouváme se za dočasný výpadek.<br>
      Zkuste to prosím znovu za chvíli.
    </p>
    <a class="btn-primary" href="{{ route('home') }}">
      <i class="fa-solid fa-house"></i> Zpět na úvod
    </a>
  </div>
</section>
@endsection
