@php
    $seleccionados = old('items', []);
    $seleccionados = is_array($seleccionados) ? $seleccionados : [];
@endphp
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Catálogo tecnológico | {{ config('app.name', 'NexaTech') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--ink:#152b36;--muted:#74848a;--line:#e8eeec;--green:#176b56;--mint:#e8f4ef;--cream:#f8f8f4;--orange:#ee8656}
        *{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:#fff;color:var(--ink);font-family:"DM Sans",sans-serif}a{color:inherit;text-decoration:none}button,input,select,textarea{font:inherit}
        .topbar{height:76px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;padding:0 max(6vw,24px);background:#fff}
        .brand{display:flex;align-items:center;gap:11px;font:800 20px Manrope,sans-serif;letter-spacing:-.8px}.brand-mark{width:36px;height:36px;border-radius:12px;background:var(--green);color:#fff;display:grid;place-items:center;font-size:20px}
        .nav{display:flex;align-items:center;gap:27px;color:#5c6c71;font-size:14px}.nav a:hover{color:var(--green)}.nav .admin-link{padding:10px 16px;border:1px solid var(--line);border-radius:9px;color:var(--ink)}
        .hero{margin:34px auto 0;max-width:1320px;padding:62px 7%;border-radius:23px;background:var(--cream);position:relative;overflow:hidden;display:flex;align-items:center;min-height:350px}
        .hero:after{content:"";position:absolute;width:340px;height:340px;border:1px solid #dce9e0;border-radius:50%;right:13%;top:11px;box-shadow:0 0 0 40px #f1f6f0,0 0 0 80px #f5f7f1}
        .hero-copy{position:relative;z-index:1;max-width:570px}.eyebrow{font-size:11px;font-weight:700;letter-spacing:1.8px;text-transform:uppercase;color:var(--green)}h1,h2,h3,p{margin-top:0}.hero h1{font:800 clamp(36px,5vw,60px)/1.08 Manrope,sans-serif;letter-spacing:-2.7px;margin:16px 0;color:var(--ink)}.hero p{font-size:16px;line-height:1.75;color:#68797e;max-width:480px;margin-bottom:24px}
        .button{display:inline-flex;align-items:center;justify-content:center;border:0;border-radius:9px;padding:13px 19px;background:var(--green);color:#fff;font-size:14px;font-weight:700;cursor:pointer;transition:.2s}.button:hover{background:#10533f;transform:translateY(-1px)}.hero .button{box-shadow:0 8px 20px #176b5626}
        .hero-art{position:absolute;z-index:1;right:12%;top:68px;width:220px;height:220px;background:#fff;border-radius:50%;display:grid;place-items:center;box-shadow:0 18px 55px #253e3118;font-size:104px}
        .benefits{max-width:1320px;padding:24px 7%;margin:0 auto;display:grid;grid-template-columns:repeat(3,1fr);gap:25px;border-bottom:1px solid var(--line)}.benefit{display:flex;gap:13px;align-items:center}.benefit-icon{width:40px;height:40px;border-radius:12px;background:var(--mint);display:grid;place-items:center;font-size:18px}.benefit strong{display:block;font-size:13px}.benefit small{color:var(--muted);font-size:12px}
        .shop{max-width:1320px;margin:0 auto;padding:60px 6% 90px}.section-heading{display:flex;align-items:end;justify-content:space-between;margin-bottom:24px}.section-heading h2{font:700 30px Manrope,sans-serif;letter-spacing:-1px;margin:7px 0}.section-heading p{margin:0;color:var(--muted);font-size:14px}
        .toolbar{display:flex;gap:10px;margin:0 0 24px}.toolbar input,.toolbar select{border:1px solid var(--line);background:#fff;border-radius:9px;padding:11px 13px;color:var(--ink)}.toolbar input{width:260px}.toolbar select{min-width:170px}.toolbar .button{padding:10px 16px}
        .shop-layout{display:grid;grid-template-columns:minmax(0,1fr) 325px;gap:28px;align-items:start}.products{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:17px}.product-card{border:1px solid var(--line);border-radius:13px;overflow:hidden;transition:.2s;background:#fff}.product-card:hover{box-shadow:0 12px 28px #193d3010;transform:translateY(-2px)}.product-card.selected{border-color:var(--green);box-shadow:0 0 0 1px var(--green)}
        .product-art{height:164px;background:#edf3ed;position:relative;overflow:hidden}.product-art>img{display:block;width:100%;height:100%;object-fit:cover;transition:opacity .15s}.product-art .category-label{position:absolute;top:11px;left:11px;background:#fff;color:var(--green);font-size:10px;font-weight:700;padding:6px 9px;border-radius:20px;box-shadow:0 2px 10px #18382d12}.gallery-thumbnails{position:absolute;right:10px;bottom:10px;display:flex;gap:5px}.gallery-thumb{width:39px;height:29px;padding:0;border:2px solid #fff;border-radius:6px;overflow:hidden;cursor:pointer;box-shadow:0 2px 8px #18382d35;opacity:.78}.gallery-thumb.active{border-color:var(--green);opacity:1}.gallery-thumb img{width:100%;height:100%;object-fit:cover;display:block}.gallery-label{position:absolute;left:11px;bottom:12px;padding:5px 8px;background:#17382dc9;color:white;border-radius:6px;font-size:9px}
        .product-info{padding:16px}.product-info .sku{color:var(--muted);font-size:10px;letter-spacing:.7px}.product-info h3{font:700 15px/1.4 Manrope,sans-serif;margin:7px 0}.product-info p{min-height:38px;color:#74848a;font-size:12px;line-height:1.5;margin:0 0 13px}.product-bottom{display:flex;justify-content:space-between;align-items:center}.price{font:700 17px Manrope,sans-serif}.stock{color:var(--muted);font-size:11px;margin-top:4px}.add-button{background:#fff;color:var(--green);border:1px solid #cadbd2;border-radius:8px;padding:9px 11px;font-size:12px;font-weight:700;cursor:pointer}.selected .add-button{background:var(--green);color:#fff}.quantity{display:none;margin-top:12px;width:90px;padding:7px;border:1px solid var(--line);border-radius:7px}.selected .quantity{display:block}
        .quote-panel{position:sticky;top:20px;border:1px solid var(--line);border-radius:14px;padding:20px;background:#fff;box-shadow:0 8px 25px #1a392c08}.quote-panel h3{font:700 19px Manrope,sans-serif;margin:0 0 4px}.quote-subtitle{color:var(--muted);font-size:12px;margin-bottom:17px}        .quote-items{display:grid;gap:10px;max-height:220px;overflow:auto}.quote-empty{font-size:13px;color:var(--muted);padding:13px 0}.quote-line{display:flex;align-items:center;justify-content:space-between;gap:8px;font-size:12px;padding-bottom:9px;border-bottom:1px solid #f0f2f0}.quote-line img{width:38px;height:34px;object-fit:cover;border-radius:5px;flex:none}.quote-line span:last-child{white-space:nowrap;font-weight:600}.quote-total{display:flex;justify-content:space-between;padding:15px 0;font-weight:700;border-bottom:1px solid var(--line);margin-bottom:16px}.field{display:block;margin-bottom:11px}.field span{display:block;font-size:11px;font-weight:600;margin-bottom:5px}.field input,.field textarea{width:100%;border:1px solid var(--line);border-radius:8px;padding:10px 11px;outline:none}.field input:focus,.field textarea:focus{border-color:var(--green)}.field textarea{resize:vertical;min-height:65px}.quote-panel .button{width:100%}.quote-panel .button:disabled{opacity:.45;cursor:not-allowed;transform:none}
        .success{max-width:1320px;margin:18px auto 0;padding:16px 22px;border-radius:10px;background:#eaf7ef;border:1px solid #cce8d5;color:#1c633b;font-size:14px}
        .empty-state{grid-column:1/-1;border:1px dashed #cadbd2;padding:50px 20px;text-align:center;border-radius:12px;color:var(--muted)}
        footer{border-top:1px solid var(--line);padding:25px 7%;display:flex;justify-content:space-between;color:var(--muted);font-size:12px}
        @media(max-width:1000px){.shop-layout{grid-template-columns:1fr}.quote-panel{position:static}.products{grid-template-columns:repeat(2,minmax(0,1fr))}.hero-art{right:8%;width:170px;height:170px;font-size:78px}.hero:after{right:0}}
        @media(max-width:650px){.topbar{height:64px}.nav{gap:12px}.nav>a:not(.admin-link){display:none}.hero{margin:18px 14px 0;padding:40px 25px;min-height:340px;align-items:flex-start}.hero-copy{max-width:100%}.hero h1{font-size:40px}.hero-art{right:15px;bottom:12px;top:auto;width:92px;height:92px;font-size:43px;opacity:.9}.hero:after{right:-150px;top:90px}.benefits{grid-template-columns:1fr;padding:18px 24px;gap:13px}.shop{padding:42px 18px 60px}.section-heading{display:block}.section-heading h2{font-size:26px}.toolbar{flex-wrap:wrap}.toolbar input{flex:1;min-width:130px}.toolbar select{flex:1;min-width:125px}.products{gap:10px}.product-art{height:115px}.gallery-thumbnails{right:6px;bottom:6px;gap:3px}.gallery-thumb{width:31px;height:24px}.gallery-label{left:6px;bottom:7px;font-size:8px}.product-info{padding:12px}.product-info h3{font-size:13px}.product-info p{font-size:11px}.price{font-size:14px}.add-button{padding:8px;font-size:11px}footer{padding:20px;gap:12px;flex-direction:column}}
    </style>
</head>
<body>
    <header class="topbar">
        <a href="{{ route('catalogo.index') }}" class="brand"><span class="brand-mark">n</span>{{ config('app.name', 'nexa') }}</a>
        <nav class="nav">
            <a href="#catalogo">Catálogo</a>
            <a href="#ventajas">Cómo funciona</a>
            @auth
                <a class="admin-link" href="{{ route('cotizaciones.index') }}">Panel de gestión</a>
            @else
                <a class="admin-link" href="{{ route('login') }}">Acceso equipo</a>
            @endauth
        </nav>
    </header>

    @if (session('cotizacion_codigo'))
        <div class="success" role="status"><strong>¡Solicitud enviada!</strong> Tu código de cotización es {{ session('cotizacion_codigo') }}. Nuestro equipo te contactará pronto.</div>
    @endif

    <section class="hero">
        <div class="hero-copy">
            <div class="eyebrow">Tecnología que te impulsa</div>
            <h1>Encuentra tu próximo gran equipo.</h1>
            <p>Explora nuestro catálogo, arma tu selección ideal y recibe una cotización personalizada. Fácil, rápido y sin compromiso.</p>
            <a class="button" href="#catalogo">Explorar productos <span aria-hidden="true">&nbsp;↓</span></a>
        </div>
        <div class="hero-art" aria-hidden="true">💻</div>
    </section>

    <section class="benefits" id="ventajas">
        <div class="benefit"><span class="benefit-icon">✦</span><div><strong>Marcas seleccionadas</strong><small>Calidad y garantía en cada equipo</small></div></div>
        <div class="benefit"><span class="benefit-icon">↗</span><div><strong>Cotización a tu medida</strong><small>Precios claros y asesoría personalizada</small></div></div>
        <div class="benefit"><span class="benefit-icon">✓</span><div><strong>Sin compromiso</strong><small>Solicita tu propuesta en minutos</small></div></div>
    </section>

    <main class="shop" id="catalogo">
        <div class="section-heading">
            <div><div class="eyebrow">Nuestro catálogo</div><h2>Equipos para cada proyecto</h2><p>Selecciona tus productos y prepara una cotización.</p></div>
            <div class="eyebrow">{{ $productos->count() }} productos disponibles</div>
        </div>
        <form class="toolbar" action="{{ route('catalogo.index') }}" method="GET">
            <input type="search" name="buscar" value="{{ $buscar }}" placeholder="Buscar producto o SKU..." aria-label="Buscar producto">
            <select name="categoria" aria-label="Filtrar por categoría">
                <option value="">Todas las categorías</option>
                @foreach ($categorias as $opcion)
                    <option value="{{ $opcion->id }}" @selected($categoria === (string) $opcion->id)>{{ $opcion->nombre_categoria }}</option>
                @endforeach
            </select>
            <button class="button" type="submit">Buscar</button>
        </form>

        <form method="POST" action="{{ route('catalogo.store') }}" id="quote-form">
            @csrf
            <div class="shop-layout">
                <section class="products" aria-label="Productos disponibles">
                    @forelse ($productos as $producto)
                        @php
                            $imagenesGaleria = collect($producto->fotos ?? [])
                                ->take(3)
                                ->map(fn ($fotografia) => [
                                    'url' => asset('storage/'.$fotografia),
                                    'alt' => 'Fotografía de '.$producto->nombre,
                                    'tipo' => 'Fotografía',
                                ])
                                ->all();
                            while (count($imagenesGaleria) < 3) {
                                $numeroIlustracion = count($imagenesGaleria) + 1;
                                $imagenesGaleria[] = [
                                    'url' => asset('images/products/technology-'.$numeroIlustracion.'.svg'),
                                    'alt' => 'Ilustración de muestra '.$numeroIlustracion.' para '.$producto->nombre,
                                    'tipo' => 'Ilustración de muestra',
                                ];
                            }
                            $imagenPrincipal = $imagenesGaleria[0];
                            $fotoParaCotizacion = !empty($producto->fotos)
                                ? asset('storage/'.$producto->fotos[0])
                                : '';
                        @endphp
                        <article class="product-card {{ array_key_exists($producto->id, $seleccionados) ? 'selected' : '' }}" data-product-id="{{ $producto->id }}" data-name="{{ $producto->nombre }}" data-price="{{ $producto->precio }}" data-photo="{{ $fotoParaCotizacion }}">
                            <div class="product-art">
                                <img class="product-main-image" src="{{ $imagenPrincipal['url'] }}" alt="{{ $imagenPrincipal['alt'] }}" loading="lazy">
                                <span class="category-label">{{ $producto->categoria->nombre_categoria }}</span>
                                <span class="gallery-label">{{ $imagenPrincipal['tipo'] }}</span>
                                <div class="gallery-thumbnails" aria-label="Fotografías de {{ $producto->nombre }}">
                                    @foreach ($imagenesGaleria as $imagen)
                                        <button
                                            class="gallery-thumb {{ $loop->first ? 'active' : '' }}"
                                            type="button"
                                            data-image="{{ $imagen['url'] }}"
                                            data-alt="{{ $imagen['alt'] }}"
                                            data-type="{{ $imagen['tipo'] }}"
                                            aria-label="Ver {{ strtolower($imagen['tipo']) }} de {{ $producto->nombre }}"
                                            aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                                        ><img src="{{ $imagen['url'] }}" alt="" loading="lazy"></button>
                                    @endforeach
                                </div>
                            </div>
                            <div class="product-info">
                                <div class="sku">{{ $producto->sku }}</div>
                                <h3>{{ $producto->nombre }}</h3>
                                <p>{{ $producto->descripcion ?: 'Equipo tecnológico de alta calidad, listo para tu próximo proyecto.' }}</p>
                                <div class="product-bottom">
                                    <div><div class="price">{{ config('catalogo.moneda', 'USD') }} {{ number_format((float) $producto->precio, 2) }}</div><div class="stock">{{ $producto->stock > 0 ? 'Disponible' : 'Consultar disponibilidad' }}</div></div>
                                    <button class="add-button" type="button">{{ array_key_exists($producto->id, $seleccionados) ? '✓ Agregado' : '+ Agregar' }}</button>
                                </div>
                                <input class="quantity" type="number" name="items[{{ $producto->id }}]" value="{{ $seleccionados[$producto->id] ?? 1 }}" min="1" max="999" @disabled(!array_key_exists($producto->id, $seleccionados)) aria-label="Cantidad de {{ $producto->nombre }}">
                            </div>
                        </article>
                    @empty
                        <div class="empty-state">No encontramos productos con esos filtros. Prueba otra búsqueda o vuelve más tarde.</div>
                    @endforelse
                </section>

                <aside class="quote-panel">
                    <h3>Tu cotización</h3>
                    <div class="quote-subtitle"><span id="item-count">0 productos seleccionados</span></div>
                    <div class="quote-items" id="quote-items"><div class="quote-empty">Agrega productos para comenzar.</div></div>
                    <div class="quote-total"><span>Total estimado</span><span id="quote-total">—</span></div>

                    <label class="field"><span>Nombre completo *</span><input name="nombre_cliente" value="{{ old('nombre_cliente') }}" autocomplete="name" required maxlength="150" placeholder="Tu nombre"></label>
                    <label class="field"><span>Empresa <small>(opcional)</small></span><input name="empresa" value="{{ old('empresa') }}" autocomplete="organization" maxlength="120" placeholder="Nombre de tu empresa"></label>
                    <label class="field"><span>Correo electrónico *</span><input type="email" name="correo" value="{{ old('correo') }}" autocomplete="email" required maxlength="150" placeholder="tu@correo.com"></label>
                    <label class="field"><span>Teléfono *</span><input name="telefono" value="{{ old('telefono') }}" autocomplete="tel" required maxlength="30" placeholder="+1 555 000 0000"></label>
                    <label class="field"><span>Cuéntanos más <small>(opcional)</small></span><textarea name="mensaje" maxlength="2000" placeholder="¿Tienes algún requerimiento especial?">{{ old('mensaje') }}</textarea></label>
                    @if ($errors->any())
                        <div role="alert" style="font-size:12px;color:#aa3434;margin-bottom:12px">{{ $errors->first() }}</div>
                    @endif
                    <button class="button" id="submit-quote" type="submit" disabled>Solicitar cotización</button>
                    <div style="text-align:center;color:var(--muted);font-size:10px;margin-top:10px">Tus datos se usan únicamente para responder a tu solicitud.</div>
                </aside>
            </div>
        </form>
    </main>
    <footer><a href="{{ route('catalogo.index') }}" class="brand"><span class="brand-mark">n</span>{{ config('app.name', 'nexa') }}</a><span>© {{ now()->year }} · Tecnología para tus ideas.</span></footer>
    <script>
        (() => {
            const cards = [...document.querySelectorAll('.product-card')];
            const quoteItems = document.getElementById('quote-items');
            const total = document.getElementById('quote-total');
            const count = document.getElementById('item-count');
            const submit = document.getElementById('submit-quote');
            const currency = @json(config('catalogo.moneda', 'USD'));
            const format = value => new Intl.NumberFormat('es', { style: 'currency', currency }).format(value);

            function updateQuote() {
                const selected = cards.filter(card => !card.querySelector('.quantity').disabled);
                quoteItems.replaceChildren();
                if (!selected.length) {
                    quoteItems.innerHTML = '<div class="quote-empty">Agrega productos para comenzar.</div>';
                }
                let amount = 0;
                selected.forEach(card => {
                    const quantity = card.querySelector('.quantity');
                    const subtotal = Number(card.dataset.price) * Number(quantity.value || 1);
                    amount += subtotal;
                    const line = document.createElement('div');
                    line.className = 'quote-line';
                    const description = document.createElement('span');
                    const lineTotal = document.createElement('span');
                    if (card.dataset.photo) {
                        const image = document.createElement('img');
                        image.src = card.dataset.photo;
                        image.alt = '';
                        image.loading = 'lazy';
                        line.append(image);
                    }
                    description.textContent = `${card.dataset.name} × ${quantity.value}`;
                    lineTotal.textContent = format(subtotal);
                    line.append(description, lineTotal);
                    quoteItems.append(line);
                });
                count.textContent = `${selected.length} ${selected.length === 1 ? 'producto seleccionado' : 'productos seleccionados'}`;
                total.textContent = selected.length ? format(amount) : '—';
                submit.disabled = !selected.length;
            }

            cards.forEach(card => {
                const button = card.querySelector('.add-button');
                const quantity = card.querySelector('.quantity');
                const mainImage = card.querySelector('.product-main-image');

                card.querySelectorAll('.gallery-thumb').forEach(thumbnail => {
                    thumbnail.addEventListener('click', () => {
                        mainImage.src = thumbnail.dataset.image;
                        mainImage.alt = thumbnail.dataset.alt;
                        card.querySelector('.gallery-label').textContent = thumbnail.dataset.type;
                        card.querySelectorAll('.gallery-thumb').forEach(option => {
                            const active = option === thumbnail;
                            option.classList.toggle('active', active);
                            option.setAttribute('aria-pressed', String(active));
                        });
                    });
                });

                button.addEventListener('click', () => {
                    quantity.disabled = !quantity.disabled;
                    card.classList.toggle('selected', !quantity.disabled);
                    button.textContent = quantity.disabled ? '+ Agregar' : '✓ Agregado';
                    updateQuote();
                });
                quantity.addEventListener('input', updateQuote);
            });

            @if ($errors->has('items'))
                document.querySelector('.quote-panel').scrollIntoView({ behavior: 'smooth', block: 'center' });
            @endif
        })();
    </script>
</body>
</html>
