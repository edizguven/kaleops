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

            <?php if(session('success')): ?>
                <div class="p-4 bg-green-600 text-white rounded-lg shadow font-bold"><?php echo e(session('success')); ?></div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="p-4 bg-red-600 text-white rounded-lg shadow font-bold"><?php echo e(session('error')); ?></div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
                <div class="p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow">
                    <p class="font-bold">Düzenleme yapılamadı:</p>
                    <ul class="list-disc pl-5 mt-1">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

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
                                ⏳ İŞLEMDE
                            </span>
                        <?php endif; ?>
                        <span class="block text-xs font-bold text-gray-500 mt-2">Adet: <?php echo e($job->quantity ?? 1); ?></span>
                        <?php if($job->priority): ?>
                            <span class="block mt-2"><span class="text-xs font-bold text-gray-400 uppercase">Öncelik:</span> <span class="px-2 py-1 rounded text-xs font-bold <?php echo e($job->priority_color_class); ?>"><?php echo e($job->priority_label); ?></span></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if($job->description): ?>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <span class="block text-xs font-bold text-gray-400 uppercase mb-1">Açıklama</span>
                        <p class="text-gray-700 whitespace-pre-wrap"><?php echo e($job->description); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md border-2 border-amber-200">
                <h4 class="font-bold text-gray-700 border-b pb-2 mb-4">✏️ İş Emri Düzenle</h4>
                <p class="text-sm text-gray-600 mb-4">Başlık, açıklama, adet veya öncelikte hata yaptıysanız aşağıdan düzeltebilirsiniz.</p>
                <form action="<?php echo e(route('admin.jobs.update', $job)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Başlık</label>
                            <input type="text" name="title" value="<?php echo e(old('title', $job->title)); ?>" required
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2">Adet</label>
                            <input type="number" name="quantity" value="<?php echo e(old('quantity', $job->quantity ?? 1)); ?>" min="1" max="999999" required
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-gray-700 font-bold mb-2">Açıklama (opsiyonel, en fazla 150 karakter)</label>
                        <textarea name="description" rows="3" maxlength="150" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="İş detayları"><?php echo e(old('description', $job->description)); ?></textarea>
                    </div>
                    <div class="mt-4">
                        <label class="block text-gray-700 font-bold mb-2">Öncelik</label>
                        <select name="priority" class="w-full max-w-xs p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">— Seçin —</option>
                            <option value="dusuk" <?php echo e(old('priority', $job->priority) === 'dusuk' ? 'selected' : ''); ?>>Düşük</option>
                            <option value="orta" <?php echo e(old('priority', $job->priority) === 'orta' ? 'selected' : ''); ?>>Orta</option>
                            <option value="yuksek" <?php echo e(old('priority', $job->priority) === 'yuksek' ? 'selected' : ''); ?>>Yüksek</option>
                            <option value="acil" <?php echo e(old('priority', $job->priority) === 'acil' ? 'selected' : ''); ?>>Acil</option>
                            <option value="cok_acil" <?php echo e(old('priority', $job->priority) === 'cok_acil' ? 'selected' : ''); ?>>Çok Acil</option>
                        </select>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg shadow-lg transition">
                            Düzenle ve Kaydet
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <h4 class="font-bold text-gray-700 border-b pb-2 mb-4">📂 Dosyalar</h4>
                <div class="space-y-3 mb-6">
                    <?php $__empty_1 = true; $__currentLoopData = $job->jobFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-center justify-between gap-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <a href="<?php echo e(route('jobfile.download', $f)); ?>" class="flex items-center gap-2 text-blue-700 font-bold hover:underline" download>
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <?php echo e($f->original_name); ?>

                            </a>
                            <form action="<?php echo e(route('admin.jobs.files.destroy', [$job, $f])); ?>" method="POST" class="inline" onsubmit="return confirm('Bu dosyayı silmek istediğinize emin misiniz?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="px-3 py-1.5 bg-red-100 text-red-700 rounded text-sm font-bold hover:bg-red-200">Sil</button>
                            </form>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-gray-500 italic">Henüz dosya yok.</p>
                    <?php endif; ?>
                </div>
                <form action="<?php echo e(route('admin.jobs.files.store', $job)); ?>" method="POST" enctype="multipart/form-data" class="border-t border-gray-200 pt-4">
                    <?php echo csrf_field(); ?>
                    <label class="block text-gray-700 font-bold mb-2">Yeni dosya ekle (tür serbest)</label>
                    <div class="flex flex-wrap items-end gap-3">
                        <input type="file" name="files[]" multiple class="text-sm text-gray-600" accept="*">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700">Ekle</button>
                    </div>
                </form>
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
                        <li class="flex justify-between">
                            <span class="text-gray-600">Torna:</span>
                            <span class="font-bold"><?php echo e($job->torna_minutes ?? '--'); ?> dk</span>
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

            <?php if($job->jobStationDetails && $job->jobStationDetails->isNotEmpty()): ?>
            <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-amber-400">
                <h4 class="font-bold text-gray-700 border-b pb-2 mb-4">📋 İstasyon Parça Detayları</h4>
                <p class="text-sm text-gray-500 mb-4">Operatörlerin girdiği parça bilgileri (Parça No, En, Boy, Yükseklik, Adet, Cinsi)</p>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase">
                                <th class="p-3">İstasyon</th>
                                <th class="p-3">Parça No</th>
                                <th class="p-3">En</th>
                                <th class="p-3">Boy</th>
                                <th class="p-3">Yükseklik</th>
                                <th class="p-3">Adet</th>
                                <th class="p-3">Cinsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $job->jobStationDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-bold"><?php echo e(\App\Models\JobStationDetail::stationLabel($d->station)); ?></td>
                                <td class="p-3"><?php echo e($d->parca_no ?? '--'); ?></td>
                                <td class="p-3"><?php echo e($d->en ?? '--'); ?></td>
                                <td class="p-3"><?php echo e($d->boy ?? '--'); ?></td>
                                <td class="p-3"><?php echo e($d->station === 'torna' ? '--' : ($d->yukseklik ?? '--')); ?></td>
                                <td class="p-3"><?php echo e($d->adet ?? '--'); ?></td>
                                <td class="p-3"><?php echo e($d->cinsi ?? '--'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

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
                    <div class="border-l-2 border-gray-200 pl-6 space-y-4">
                        <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-bold text-indigo-800 text-lg">Üretim Maliyeti:</span>
                                <span class="font-bold text-indigo-600 text-2xl">$<?php echo e(number_format($job->unit_production_cost, 2)); ?></span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">1 adet için tüm istasyonların toplam maliyeti</p>
                        </div>
                        <?php $qty = (int) ($job->quantity ?? 1); ?>
                        <?php if($qty > 0): ?>
                        <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-amber-800">1 Adet Maliyeti:</span>
                                    <span class="font-bold">$<?php echo e(number_format($job->unit_production_cost, 2)); ?></span>
                                </div>
                                <div class="flex justify-between font-bold text-amber-900">
                                    <span><?php echo e($qty); ?> Adet × $<?php echo e(number_format($job->unit_production_cost, 2)); ?> =</span>
                                    <span>$<?php echo e(number_format($job->total_quantity_cost, 2)); ?></span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Toplam üretim maliyeti (adet × 1 adet maliyeti)</p>
                        </div>
                        <?php endif; ?>
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
                            <span class="text-gray-600">Toplam Üretim Maliyeti (<?php echo e($job->quantity ?? 1); ?> adet):</span>
                            <span class="font-bold">$<?php echo e(number_format($job->total_quantity_cost, 2)); ?></span>
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
<?php endif; ?><?php /**PATH /Users/edizguven/Desktop/KaleSavunma/public_html/resources/views/admin/jobs/show.blade.php ENDPATH**/ ?>