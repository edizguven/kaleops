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
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('İş Detay Raporu')); ?> : <span class="font-mono text-blue-600"><?php echo e($job->job_no); ?></span>
            </h2>
            <a href="<?php echo e(route('admin.jobs.index')); ?>" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 text-sm font-bold">
                &larr; Listeye Dön
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-6 rounded-xl shadow-md border-l-8 <?php echo e($job->is_completed ? 'border-green-500' : 'border-yellow-500'); ?>">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo e($job->title); ?></h3>
                        <p class="text-gray-500 text-sm mt-1">Oluşturulma Tarihi: <?php echo e($job->created_at->format('d.m.Y H:i')); ?></p>
                    </div>
                    <div class="text-right">
                        <span class="block text-xs font-bold text-gray-400 uppercase">Durum</span>
                        <?php if($job->is_completed): ?>
                            <span class="px-4 py-2 rounded-full bg-green-100 text-green-800 font-bold text-sm inline-block mt-1">
                                ✅ TAMAMLANDI
                            </span>
                        <?php else: ?>
                            <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-800 font-bold text-sm inline-block mt-1">
                                ⏳ İŞLEMDE: <?php echo e(strtoupper($job->current_stage)); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <h4 class="font-bold text-gray-700 border-b pb-2 mb-4">📂 Teknik Dosyalar (Başlangıç)</h4>
                <div class="flex gap-4">
                    <?php if($job->image_path): ?>
                        <a href="<?php echo e(asset('storage/' . $job->image_path)); ?>" target="_blank" class="flex items-center px-4 py-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 font-bold border border-blue-200">
                            🖼️ Teknik Resim
                        </a>
                    <?php else: ?>
                        <span class="flex items-center px-4 py-3 bg-gray-100 text-gray-500 rounded-lg border border-gray-200">
                            🖼️ Teknik Resim (Yok)
                        </span>
                    <?php endif; ?>
                    <?php if($job->pdf_path): ?>
                        <a href="<?php echo e(asset('storage/' . $job->pdf_path)); ?>" target="_blank" class="flex items-center px-4 py-3 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 font-bold border border-red-200">
                            📄 PDF Dosyası
                        </a>
                    <?php else: ?>
                        <span class="flex items-center px-4 py-3 bg-gray-100 text-gray-500 rounded-lg border border-gray-200">
                            📄 PDF Dosyası (Yok)
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h4 class="font-bold text-gray-700 border-b pb-2 mb-4">⏱️ Üretim Süreleri</h4>
                    <ul class="space-y-4">
                        <li class="flex justify-between">
                            <span class="text-gray-600">CAM İstasyonu:</span>
                            <span class="font-bold"><?php echo e($job->cam_minutes ?? '--'); ?> dk</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-600">Lazer İstasyonu:</span>
                            <span class="font-bold"><?php echo e($job->lazer_minutes ?? '--'); ?> dk</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-600">CMM Ölçüm:</span>
                            <span class="font-bold"><?php echo e($job->cmm_minutes ?? '--'); ?> dk</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-600">Tesviye:</span>
                            <span class="font-bold"><?php echo e($job->tesviye_minutes ?? '--'); ?> dk</span>
                        </li>
                        <li class="border-t pt-2 flex justify-between text-indigo-700">
                            <span class="font-bold">Toplam Süre:</span>
                            <span class="font-bold">
                                <?php echo e($job->total_minutes); ?> dk
                            </span>
                        </li>
                    </ul>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h4 class="font-bold text-gray-700 border-b pb-2 mb-4">📦 Planlama & Lojistik</h4>
                    <ul class="space-y-4">
                        <li>
                            <span class="block text-xs text-gray-400">Planlanan Tarih</span>
                            <span class="font-bold text-gray-800"><?php echo e($job->planning_date ? \Carbon\Carbon::parse($job->planning_date)->format('d.m.Y') : '--'); ?></span>
                        </li>
                        <li>
                            <span class="block text-xs text-gray-400">Paket Tipi</span>
                            <span class="font-bold text-gray-800"><?php echo e($job->packaging_type ?? '--'); ?></span>
                        </li>
                        <li>
                            <span class="block text-xs text-gray-400 mb-1">İrsaliye Dosyası</span>
                            <?php if($job->delivery_note_path): ?>
                                <a href="<?php echo e(asset('storage/' . $job->delivery_note_path)); ?>" target="_blank" class="text-blue-600 underline font-bold text-sm">Dosyayı Görüntüle</a>
                            <?php else: ?>
                                <span class="text-gray-400 italic text-sm">Henüz yüklenmedi</span>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border border-green-100 bg-green-50">
                    <h4 class="font-bold text-green-800 border-b border-green-200 pb-2 mb-4">💰 Muhasebe & Finans</h4>
                    <ul class="space-y-4">
                        <li class="flex justify-between">
                            <span class="text-green-700 text-sm">Toplam Tutar:</span>
                            <span class="font-bold text-lg">$<?php echo e(number_format($job->total_amount ?? 0, 2)); ?></span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-green-700 text-sm">Tahsil Edilen:</span>
                            <span class="font-bold text-lg text-green-600">$<?php echo e(number_format($job->paid_amount ?? 0, 2)); ?></span>
                        </li>
                        <li>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-2">
                                <?php
                                    $totalAmount = $job->total_amount ?? 0;
                                    $paidAmount = $job->paid_amount ?? 0;
                                    $percent = ($totalAmount > 0) ? ($paidAmount / $totalAmount) * 100 : 0;
                                ?>
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: <?php echo e($percent); ?>%"></div>
                            </div>
                            <span class="text-xs text-gray-500 mt-1 block">%<?php echo e(round($percent)); ?> Ödendi</span>
                        </li>
                        <li class="pt-2">
                             <span class="block text-xs text-green-700 mb-1">Fatura</span>
                             <?php if($job->invoice_path): ?>
                                <a href="<?php echo e(asset('storage/' . $job->invoice_path)); ?>" target="_blank" class="text-green-700 underline font-bold text-sm">📄 Faturayı Görüntüle</a>
                            <?php else: ?>
                                <span class="text-gray-400 italic text-sm">Kesilmedi</span>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Üretim Maliyetleri -->
            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-500">
                <h4 class="font-bold text-gray-700 border-b pb-2 mb-4">💰 Üretim Maliyetleri (USD)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <ul class="space-y-4">
                        <li class="flex justify-between items-center">
                            <span class="text-gray-600">CAM İstasyonu:</span>
                            <div class="text-right">
                                <span class="font-bold text-green-600">$<?php echo e(number_format($job->cam_cost, 2)); ?></span>
                                <span class="text-xs text-gray-400 ml-2">(<?php echo e($job->cam_minutes ?? 0); ?> dk)</span>
                            </div>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="text-gray-600">Lazer İstasyonu:</span>
                            <div class="text-right">
                                <span class="font-bold text-green-600">$<?php echo e(number_format($job->lazer_cost, 2)); ?></span>
                                <span class="text-xs text-gray-400 ml-2">(<?php echo e($job->lazer_minutes ?? 0); ?> dk)</span>
                            </div>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="text-gray-600">CMM Ölçüm:</span>
                            <div class="text-right">
                                <span class="font-bold text-green-600">$<?php echo e(number_format($job->cmm_cost, 2)); ?></span>
                                <span class="text-xs text-gray-400 ml-2">(<?php echo e($job->cmm_minutes ?? 0); ?> dk)</span>
                            </div>
                        </li>
                        <li class="flex justify-between items-center">
                            <span class="text-gray-600">Tesviye:</span>
                            <div class="text-right">
                                <span class="font-bold text-green-600">$<?php echo e(number_format($job->tesviye_cost, 2)); ?></span>
                                <span class="text-xs text-gray-400 ml-2">(<?php echo e($job->tesviye_minutes ?? 0); ?> dk)</span>
                            </div>
                        </li>
                        <?php if($job->planning_date): ?>
                        <li class="flex justify-between items-center">
                            <span class="text-gray-600">Planlama:</span>
                            <span class="font-bold text-green-600">$<?php echo e(number_format($job->planning_cost, 2)); ?></span>
                        </li>
                        <?php endif; ?>
                        <?php if($job->packaging_type): ?>
                        <li class="flex justify-between items-center">
                            <span class="text-gray-600">Paketleme (<?php echo e($job->packaging_type); ?>):</span>
                            <span class="font-bold text-green-600">$<?php echo e(number_format($job->packaging_cost, 2)); ?></span>
                        </li>
                        <?php endif; ?>
                        <?php if($job->delivery_note_path): ?>
                        <li class="flex justify-between items-center">
                            <span class="text-gray-600">Lojistik:</span>
                            <span class="font-bold text-green-600">$<?php echo e(number_format($job->logistics_cost, 2)); ?></span>
                        </li>
                        <?php endif; ?>
                    </ul>
                    <div class="border-l-2 border-gray-200 pl-6">
                        <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-bold text-indigo-800 text-lg">Toplam Üretim Maliyeti:</span>
                                <span class="font-bold text-indigo-600 text-2xl">$<?php echo e(number_format($job->total_production_cost, 2)); ?></span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Tüm istasyonların toplam maliyeti</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kar/Zarar Analizi -->
            <?php if($job->total_amount): ?>
            <?php
                $isProfit = $job->profit_loss >= 0;
                $bgColor = $isProfit ? 'green' : 'red';
            ?>
            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 <?php echo e($isProfit ? 'border-green-500' : 'border-red-500'); ?>">
                <h4 class="font-bold text-gray-700 border-b pb-2 mb-4">📊 Kar/Zarar Analizi</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <ul class="space-y-4">
                        <li class="flex justify-between">
                            <span class="text-gray-600">Toplam Gelir (Muhasebe):</span>
                            <span class="font-bold">$<?php echo e(number_format($job->total_amount, 2)); ?></span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-600">Toplam Üretim Maliyeti:</span>
                            <span class="font-bold">$<?php echo e(number_format($job->total_production_cost, 2)); ?></span>
                        </li>
                    </ul>
                    <div class="p-4 rounded-lg border <?php echo e($isProfit ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'); ?>">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-bold text-lg <?php echo e($isProfit ? 'text-green-800' : 'text-red-800'); ?>">
                                <?php echo e($isProfit ? '✅ Kar' : '❌ Zarar'); ?>:
                            </span>
                            <span class="font-bold text-2xl <?php echo e($isProfit ? 'text-green-600' : 'text-red-600'); ?>">
                                $<?php echo e(number_format(abs($job->profit_loss), 2)); ?>

                            </span>
                        </div>
                        <div class="mt-2">
                            <span class="text-sm text-gray-600">Kar/Zarar Yüzdesi: </span>
                            <span class="font-bold <?php echo e($isProfit ? 'text-green-600' : 'text-red-600'); ?>">
                                <?php echo e(number_format($job->profit_loss_percentage, 2)); ?>%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

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
<?php endif; ?><?php /**PATH /Users/edizguven/Desktop/total/KaleSavunma/resources/views/admin/jobs/show.blade.php ENDPATH**/ ?>