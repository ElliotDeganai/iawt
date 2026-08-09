<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['url']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['url']); ?>
<?php foreach (array_filter((['url']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>
<tr>
<td class="header">
<a href="<?php echo e($url); ?>" style="display:inline-block;">
<?php
    $logo = \App\Models\Setting::get('logo', '/images/logo-default.svg');
    $logoUrl = str_starts_with($logo, 'http') ? $logo : url($logo);
?>
<img src="<?php echo e($logoUrl); ?>" alt="" width="36" height="36" style="width:36px;height:36px;border-radius:50%;object-fit:cover;vertical-align:middle;margin-right:8px;">
<?php echo e($slot); ?>

</a>
</td>
</tr><?php /**PATH C:\Users\LENOVO\Downloads\iawt\resources\views/vendor/mail/html/header.blade.php ENDPATH**/ ?>