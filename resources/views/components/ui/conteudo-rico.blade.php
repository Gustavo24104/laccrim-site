@props(['html'])

{{-- Só receba HTML já passado por App\Contracts\SanitizadorHtml. --}}
<div {{ $attributes->class('flex flex-col gap-4 leading-relaxed [&_a]:font-semibold [&_a]:underline [&_a]:underline-offset-4 [&_blockquote]:border-l-4 [&_blockquote]:border-destaque [&_blockquote]:pl-4 [&_blockquote]:italic [&_h3]:font-serif [&_h3]:text-xl [&_h3]:font-semibold [&_h3]:text-titulo [&_h4]:text-lg [&_h4]:font-semibold [&_h4]:text-titulo [&_ol]:list-decimal [&_ol]:pl-6 [&_ul]:list-disc [&_ul]:pl-6') }}>
    {!! $html !!}
</div>
