<!-- VIEW 10: perfil/edit.blade.php -->
<!-- resources/views/perfil/edit.blade.php -->

@extends('layout.app')


@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-purple-50 py-12">
    <div class="container mx-auto px-4 max-w-2xl">
        
        <!-- Card de Edição -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-6">
                <h1 class="text-3xl font-bold text-white">✏️ Editar Perfil</h1>
                <p class="text-blue-100 mt-2">Atualize suas informações pessoais</p>
            </div>

            <!-- Formulário -->
            <form action="{{ route('perfil.update') }}" method="PUT" class="p-8 space-y-6">
                @csrf
                @method('PUT')

                <!-- Bio -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">📝 Bio (até 500 caracteres)</label>
                    <textarea 
                        name="bio" 
                        maxlength="500"
                        rows="5"
                        placeholder="Conte um pouco sobre você, seus interesses em RPG, etc..."
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 resize-none"
                    >{{ Auth::user()->bio }}</textarea>
                    @error('bio')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Website -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">🔗 Website (opcional)</label>
                    <input 
                        type="url" 
                        name="website" 
                        placeholder="https://seu-site.com"
                        value="{{ Auth::user()->website }}"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >
                    @error('website')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Privacidade -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">🔒 Privacidade do Perfil</label>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 cursor-pointer transition-all" onclick="document.getElementById('privacidade_publico').checked = true">
                            <input type="radio" id="privacidade_publico" name="privacidade_perfil" value="publico" {{ Auth::user()->privacidade_perfil === 'publico' ? 'checked' : '' }} class="w-4 h-4">
                            <div>
                                <p class="font-bold text-gray-800">🌐 Público</p>
                                <p class="text-sm text-gray-500">Qualquer um pode ver seu perfil e postagens</p>
                            </div>
                        </label>
                        
                        <label class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-xl hover:border-purple-500 hover:bg-purple-50 cursor-pointer transition-all" onclick="document.getElementById('privacidade_privado').checked = true">
                            <input type="radio" id="privacidade_privado" name="privacidade_perfil" value="privado" {{ Auth::user()->privacidade_perfil === 'privado' ? 'checked' : '' }} class="w-4 h-4">
                            <div>
                                <p class="font-bold text-gray-800">🔒 Privado</p>
                                <p class="text-sm text-gray-500">Apenas seguidores aprovados podem ver seu perfil</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex gap-4 pt-6 border-t border-gray-200">
                    <button 
                        type="submit" 
                        class="flex-1 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:shadow-lg font-bold transition-all"
                    >
                        ✅ Salvar Alterações
                    </button>
                    <a 
                        href="{{ route('perfil.show', Auth::user()->username) }}" 
                        class="flex-1 py-3 bg-gray-300 text-gray-800 rounded-xl hover:bg-gray-400 font-bold text-center transition-all"
                    >
                        ❌ Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection