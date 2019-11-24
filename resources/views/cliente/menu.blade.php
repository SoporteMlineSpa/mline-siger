@section('nav-menu')
  <li class="mr-6 my-2 md:my-0">
    <a href="#" class="block py-1 md:py-3 pl-1 align-middle text-orange-600 no-underline hover:text-gray-900 border-b-2 border-orange-600 hover:border-orange-600">
      <i class="fas fa-home fa-fw mr-3 text-orange-600"></i><span class="pb-1 md:pb-0 text-sm">Home</span>
    </a>
  </li>
  <li class="mr-6 my-2 md:my-0">
    <a href="{{route('pedidos.index')}}" class="block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-white hover:border-pink-500">
      <i class="fas fa-tasks fa-fw mr-3"></i><span class="pb-1 md:pb-0 text-sm">Ordenes de Pedido</span>
    </a>
  </li>
  <li class="mr-6 my-2 md:my-0">
    <a href="#" class="block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-white hover:border-purple-500">
      <i class="fas fa-wallet fa-fw mr-3"></i><span class="pb-1 md:pb-0 text-sm">Presupuesto</span>
    </a>
  </li>
  @if(Auth::user()->holding()->exists())
  <li class="mr-6 my-2 md:my-0">
    <a href="{{route('empresas.index')}}" class="block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-white hover:border-purple-500">
      <i class="fas fa-building fa-fw mr-3"></i><span class="pb-1 md:pb-0 text-sm">Empresas</span>
    </a>
  </li>
  @endif
  @if(Auth::user()->empresa()->exists())
  <li class="mr-6 my-2 md:my-0">
    <a href="#" class="block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-white hover:border-purple-500">
      <i class="fas fa-building fa-fw mr-3"></i><span class="pb-1 md:pb-0 text-sm">Puntos de Abastecimiento</span>
    </a>
  </li>
  @endif
@endsection
