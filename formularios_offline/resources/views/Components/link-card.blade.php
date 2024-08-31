<div class="projeto-card" data-action="{{ $action }}">
    <div class="card-conteudo">
        <div class="card-titulo">
            <span> {{ $titulo }}
                <i class="{{ $icone }}"></i>
            </span>
        </div>
        <div class="card-descricao">
            <span> {{ $descricao }} </span>
        </div>
    </div>
</div>

@push('scripts')
    <script type="application/javascript">
        $(document).ready(function(){
            let url = '{{ $link }}';
            let action = '{{ $action }}';
            $('.projeto-card[data-action="'+action+'"]').on('click', () => {
                $(location).attr('href', url);
            })
        });
    </script>
@endpush


