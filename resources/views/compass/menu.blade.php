@section('nav-menu')
  <li class="mr-6 my-2 md:my-0">
    <a href="{{route('compass.home')}}" class="block py-1 md:py-3 pl-1 align-middle text-orange-600 no-underline hover:text-gray-900 border-b-2 border-orange-600 hover:border-orange-600">
      <i class="fas fa-home fa-fw mr-3 text-orange-600"></i><span class="pb-1 md:pb-0 text-sm">Home</span>
    </a>
  </li>
  <li class="mr-6 my-2 md:my-0">
    <a href="{{ route('compass.pedidos.index') }}" class="block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-white hover:border-pink-500">
      <i class="fas fa-tasks fa-fw mr-3"></i><span class="pb-1 md:pb-0 text-sm">Ordenes de Pedido</span>
    </a>
  </li>
  <li class="mr-6 my-2 md:my-0">
    <a href="{{route('productos.index')}}" class="block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-white hover:border-purple-500">
      <i class="fas fa-wallet fa-fw mr-3"></i><span class="pb-1 md:pb-0 text-sm">Productos</span>
    </a>
  </li>
@endsection
