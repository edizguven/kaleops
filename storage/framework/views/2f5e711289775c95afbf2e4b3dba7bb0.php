<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Maliyet Ayarları')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <?php if(session('success')): ?>
                <div class="mb-4 p-4 bg-green-500 text-white rounded-lg shadow font-bold">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-md">
                    <p class="font-bold">Hata:</p>
                    <ul class="list-disc pl-5 mt-2">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- İstasyon Maliyetleri -->
            <div class="bg-white shadow-lg rounded-xl p-6">
                <h3 class="text-xl font-bold mb-6 border-b pb-4">İstasyon Maliyetleri (Dakika Başına - USD)</h3>
                
                <form action="<?php echo e(route('admin.settings.update.stations')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $stations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $station): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700"><?php echo e($station->station_name); ?></label>
                                    <p class="text-xs text-gray-500 mt-1">Kod: <?php echo e($station->station_code); ?></p>
                                </div>
                                <div>
                                    <input type="hidden" name="stations[<?php echo e($loop->index); ?>][id]" value="<?php echo e($station->id); ?>">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" 
                                               step="0.01" 
                                               name="stations[<?php echo e($loop->index); ?>][cost_per_minute]" 
                                               value="<?php echo e($station->cost_per_minute); ?>" 
                                               required
                                               min="0"
                                               class="pl-7 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-900">
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600">
                                    USD/dakika
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition transform hover:scale-105">
                            💾 İstasyon Maliyetlerini Kaydet
                        </button>
                    </div>
                </form>
            </div>

            <!-- Paketleme Fiyatları -->
            <div class="bg-white shadow-lg rounded-xl p-6">
                <h3 class="text-xl font-bold mb-6 border-b pb-4">Paketleme Fiyatları (Sabit - USD)</h3>
                
                <form action="<?php echo e(route('admin.settings.update.packages')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700"><?php echo e($package->package_name); ?></label>
                                    <p class="text-xs text-gray-500 mt-1">Tip: <?php echo e($package->package_type); ?></p>
                                </div>
                                <div>
                                    <input type="hidden" name="packages[<?php echo e($loop->index); ?>][id]" value="<?php echo e($package->id); ?>">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" 
                                               step="0.01" 
                                               name="packages[<?php echo e($loop->index); ?>][price]" 
                                               value="<?php echo e($package->price); ?>" 
                                               required
                                               min="0"
                                               class="pl-7 w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900">
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600">
                                    USD (sabit fiyat)
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition transform hover:scale-105">
                            💾 Paketleme Fiyatlarını Kaydet
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>












<?php /**PATH /Users/edizguven/Desktop/public_html/resources/views/admin/settings/index.blade.php ENDPATH**/ ?>