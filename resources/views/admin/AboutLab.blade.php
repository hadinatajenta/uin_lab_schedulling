@extends('layouts.app')

@section('title', 'Tentang LAB')

@section('content')
    {{-- SOP --}}
    <section class="bg-white wedusbg-gray-900">
        <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
            <div class=" lg:mt-0 lg:col-span-5 lg:flex">
                <img src="https://img.freepik.com/free-vector/process-optimization-concept-illustration_114360-21583.jpg?w=740&t=st=1717837674~exp=1717838274~hmac=e3281dddc79904938a2a6dbb3dfc170a6c5c7ab36ac08cf0b0a1b1618aed908a"
                    alt="mockup">
            </div>
            <div class="mr-auto place-self-center lg:col-span-7">
                <h1
                    class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl wedustext-white">
                    Standar Operasional Prosedur</h1>
                <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl wedustext-gray-400">Kamu bisa
                    melihat Standar Operasioal Prosedur dari Lab Biologi ini disini sebelum memulai praktikum / menggunakan
                    lab.</p>
                <a href="#" data-modal-target="sop" data-modal-toggle="sop"
                    class="inline-flex items-center self-end  px-5 py-3 mr-3 text-base font-medium text-center bg-gray-800 text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 wedusfocus:ring-primary-900">
                    Lihat SOP
                    <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </a>

                {{-- Modal --}}
                <div id="sop" tabindex="-1" aria-hidden="true"
                    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow wedusbg-gray-700">
                            <!-- Modal header -->
                            <div
                                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t wedusborder-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 wedustext-white">
                                    PERATURAN DAN TATA TERTIB LABORATORIUM BIOLOGI
                                </h3>
                                <button type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center wedushover:bg-gray-600 wedushover:text-white"
                                    data-modal-hide="sop">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <div class="p-4 md:p-5 space-y-4">
                                <p class="text-base leading-relaxed text-gray-500 wedustext-gray-400">
                                    {!! $aboutLab->sop !!}
                                </p>

                            </div>
                            <!-- Modal footer -->
                            <div
                                class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b wedusborder-gray-600">
                                <button data-modal-hide="sop" type="button"
                                    class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center wedusbg-gray-600 wedushover:bg-gray-700 wedusfocus:ring-gray-800">I
                                    accept</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Struktur organisasi --}}
    <div class="container mx-auto my-8">
        <h2 class="text-2xl font-bold mb-4">Struktur Organisasi</h2>
        <div class="flex justify-center">
            <img src="{{ asset('storage/' . $aboutLab->stuktur) }}" alt="Struktur Organisasi" class="max-w-full h-auto">
        </div>
    </div>

    <div class="container mx-auto my-8">
        <div class="flex justify-end">
            <a href="{{ route('editInfoLab') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Edit
            </a>
        </div>
    </div>
@endsection
