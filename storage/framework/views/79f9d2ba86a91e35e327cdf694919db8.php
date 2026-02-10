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
            <?php echo e(__('Operatör Paneli')); ?> - <span class="text-blue-600 uppercase"><?php echo e(auth()->user()->role); ?> İstasyonu</span>
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <?php if(session('success')): ?>
                <div class="mb-6 p-4 bg-green-500 text-white rounded-lg shadow font-bold flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-6 p-4 bg-red-500 text-white rounded-lg shadow font-bold">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                    <ul class="list-disc pl-5">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="space-y-6">
                <?php $__empty_1 = true; $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border-l-8 border-blue-500 transition hover:shadow-2xl">
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                                <div>
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">İş Emri No</span>
                                    <h3 class="text-3xl font-black text-gray-800 font-mono"><?php echo e($job->job_no); ?></h3>
                                    <p class="text-lg text-gray-600 mt-1 font-medium"><?php echo e($job->title); ?></p>
                                    <span class="block text-sm font-bold text-gray-500 mt-1">Adet: <?php echo e($job->quantity ?? 1); ?></span>
                                </div>
                                <div class="flex flex-wrap gap-2 mt-4 md:mt-0">
                                    <?php $__empty_2 = true; $__currentLoopData = $job->jobFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                        <a href="<?php echo e(route('jobfile.download', $f)); ?>" class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-blue-600 hover:text-white transition font-bold text-sm border border-gray-300" download>
                                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <?php echo e(Str::limit($f->original_name, 20)); ?>

                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                        <span class="text-gray-500 text-sm">Dosya yok</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="border-t border-gray-100 my-4"></div>

                            <?php if(auth()->user()->role == 'cam'): ?>
                                <form action="<?php echo e(route('operator.update', $job->id)); ?>" method="POST" class="bg-blue-50 p-6 rounded-xl border border-blue-100">
                                    <?php echo csrf_field(); ?>
                                    <div class="flex flex-col md:flex-row items-end gap-4">
                                        <div class="w-full">
                                            <label class="block text-blue-900 font-bold text-sm mb-2 uppercase">CAM İşlem Süresi (Dakika)</label>
                                            <input type="number" name="cam_minutes" required min="1" placeholder="Örn: 45" 
                                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 text-lg">
                                        </div>
                                        <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition transform hover:scale-105 whitespace-nowrap">
                                            KAYDET VE GÖNDER
                                        </button>
                                    </div>
                                </form>
                            <?php endif; ?>

                            <?php if(auth()->user()->role == 'lazer'): ?>
                                <form action="<?php echo e(route('operator.update', $job->id)); ?>" method="POST" class="bg-red-50 p-6 rounded-xl border border-red-100">
                                    <?php echo csrf_field(); ?>
                                    <div class="flex flex-col md:flex-row items-end gap-4">
                                        <div class="w-full">
                                            <label class="block text-red-900 font-bold text-sm mb-2 uppercase">Lazer İşlem Süresi (Dakika)</label>
                                            <input type="number" name="lazer_minutes" required min="1" placeholder="Örn: 50" 
                                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 py-3 text-lg">
                                        </div>
                                        <button type="submit" class="w-full md:w-auto bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition transform hover:scale-105 whitespace-nowrap">
                                            KAYDET VE GÖNDER
                                        </button>
                                    </div>
                                </form>
                            <?php endif; ?>

                            <?php if(auth()->user()->role == 'cmm'): ?>
                                <form action="<?php echo e(route('operator.update', $job->id)); ?>" method="POST" class="bg-purple-50 p-6 rounded-xl border border-purple-100">
                                    <?php echo csrf_field(); ?>
                                    <div class="flex flex-col md:flex-row items-end gap-4">
                                        <div class="w-full">
                                            <label class="block text-purple-900 font-bold text-sm mb-2 uppercase">CMM İşlem Süresi (Dakika)</label>
                                            <input type="number" name="cmm_minutes" required min="1" placeholder="Örn: 30" 
                                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 py-3 text-lg">
                                        </div>
                                        <button type="submit" class="w-full md:w-auto bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition transform hover:scale-105 whitespace-nowrap">
                                            KAYDET VE GÖNDER
                                        </button>
                                    </div>
                                </form>
                            <?php endif; ?>

                            <?php if(auth()->user()->role == 'tesviye'): ?>
                                <form action="<?php echo e(route('operator.update', $job->id)); ?>" method="POST" class="bg-orange-50 p-6 rounded-xl border border-orange-100">
                                    <?php echo csrf_field(); ?>
                                    <div class="flex flex-col md:flex-row items-end gap-4">
                                        <div class="w-full">
                                            <label class="block text-orange-900 font-bold text-sm mb-2 uppercase">Tesviye İşlem Süresi (Dakika)</label>
                                            <input type="number" name="tesviye_minutes" required min="1" placeholder="Örn: 60" 
                                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 py-3 text-lg">
                                        </div>
                                        <button type="submit" class="w-full md:w-auto bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition transform hover:scale-105 whitespace-nowrap">
                                            KAYDET VE GÖNDER
                                        </button>
                                    </div>
                                </form>
                            <?php endif; ?>

                            <?php if(auth()->user()->role == 'planning'): ?>
                                <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                    <h4 class="text-sm font-bold text-gray-600 uppercase mb-3">İstasyon süreleri (CAM, Lazer, CMM, Tesviye)</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                                        <div><span class="text-gray-500">CAM:</span> <span class="font-bold"><?php echo e($job->cam_minutes ?? '--'); ?> dk</span></div>
                                        <div><span class="text-gray-500">Lazer:</span> <span class="font-bold"><?php echo e($job->lazer_minutes ?? '--'); ?> dk</span></div>
                                        <div><span class="text-gray-500">CMM:</span> <span class="font-bold"><?php echo e($job->cmm_minutes ?? '--'); ?> dk</span></div>
                                        <div><span class="text-gray-500">Tesviye:</span> <span class="font-bold"><?php echo e($job->tesviye_minutes ?? '--'); ?> dk</span></div>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-2">Toplam: <?php echo e($job->total_minutes); ?> dk</p>
                                </div>
                                <form action="<?php echo e(route('operator.update', $job->id)); ?>" method="POST" class="bg-indigo-50 p-6 rounded-xl border border-indigo-100">
                                    <?php echo csrf_field(); ?>
                                    <div class="space-y-4">
                                        <div class="flex flex-col md:flex-row items-end gap-4">
                                            <div class="w-full">
                                                <label class="block text-indigo-900 font-bold text-sm mb-2 uppercase">Üretim Planlama / Termin Tarihi</label>
                                                <input type="date" name="planning_date" required
                                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3 text-lg">
                                            </div>
                                            <div class="w-full md:max-w-[180px]">
                                                <label class="block text-indigo-900 font-bold text-sm mb-2 uppercase">İş günü ekle (opsiyonel)</label>
                                                <input type="number" name="extra_days" value="0" min="0" max="365" placeholder="0, 3, 5, 10..."
                                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3 text-lg">
                                                <p class="text-xs text-gray-500 mt-1">Tarihe eklenecek gün sayısı</p>
                                            </div>
                                        </div>
                                        <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition transform hover:scale-105 whitespace-nowrap">
                                            TARİHİ KAYDET
                                        </button>
                                    </div>
                                </form>
                            <?php endif; ?>

                            <?php if(auth()->user()->role == 'packaging'): ?>
                                <form action="<?php echo e(route('operator.update', $job->id)); ?>" method="POST" class="bg-pink-50 p-6 rounded-xl border border-pink-100">
                                    <?php echo csrf_field(); ?>
                                    <div class="flex flex-col md:flex-row items-end gap-4">
                                        <div class="w-full">
                                            <label class="block text-pink-900 font-bold text-sm mb-2 uppercase">Paketleme Tipi Seçimi</label>
                                            <select name="packaging_type" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 py-3 text-lg">
                                                <option value="Kucuk">Küçük Paket</option>
                                                <option value="Orta">Orta Paket</option>
                                                <option value="Buyuk">Büyük Paket</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="w-full md:w-auto bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition transform hover:scale-105 whitespace-nowrap">
                                            SEÇİMİ KAYDET
                                        </button>
                                    </div>
                                </form>
                            <?php endif; ?>

                            <?php if(auth()->user()->role == 'logistics'): ?>
                                <form action="<?php echo e(route('operator.update', $job->id)); ?>" method="POST" enctype="multipart/form-data" class="bg-teal-50 p-6 rounded-xl border border-teal-100">
                                    <?php echo csrf_field(); ?>
                                    <div class="flex flex-col gap-4">
                                        <div class="w-full">
                                            <label class="block text-teal-900 font-bold text-sm mb-2 uppercase">İrsaliye Dosyası Yükle</label>
                                            <div class="bg-white p-2 rounded border border-teal-200">
                                                <input type="file" name="delivery_note" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                                            </div>
                                        </div>
                                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition transform hover:scale-105">
                                            İRSALİYE YÜKLE VE MUHASEBEYE GÖNDER
                                        </button>
                                    </div>
                                </form>
                            <?php endif; ?>

                            <?php if(auth()->user()->role == 'accounting'): ?>
                                <form action="<?php echo e(route('operator.update', $job->id)); ?>" method="POST" enctype="multipart/form-data" class="bg-green-50 p-6 rounded-xl border border-green-100">
                                    <?php echo csrf_field(); ?>
                                    <h4 class="font-bold text-green-900 mb-6 border-b border-green-200 pb-2 text-lg">Muhasebe ve Kapanış İşlemleri</h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1 uppercase">Toplam Tutar</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">₺</span>
                                                </div>
                                                <input type="number" step="0.01" name="total_amount" required class="pl-7 w-full rounded border-gray-300 focus:ring-green-500 focus:border-green-500" placeholder="0.00">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1 uppercase">Tahsil Edilen Tutar</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">₺</span>
                                                </div>
                                                <input type="number" step="0.01" name="paid_amount" required class="pl-7 w-full rounded border-gray-300 focus:ring-green-500 focus:border-green-500" placeholder="0.00">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1 uppercase">Ödeme Durumu</label>
                                            <select name="payment_status" class="w-full rounded border-gray-300 focus:ring-green-500 focus:border-green-500">
                                                <option value="paid">✅ Tamamı Tahsil Edildi</option>
                                                <option value="partial">⚠️ Kısmi Ödeme</option>
                                                <option value="unpaid">❌ Ödeme Bekliyor</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1 uppercase">Fatura Yükle</label>
                                            <input type="file" name="invoice" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-100 file:text-green-700 hover:file:bg-green-200">
                                        </div>
                                    </div>

                                    <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white font-bold py-4 rounded-lg shadow-xl transition transform hover:scale-105 uppercase tracking-wider">
                                        İŞLEMİ TAMAMLA VE KAPAT 🚀
                                    </button>
                                </form>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="bg-white p-12 text-center rounded-xl shadow-lg border border-gray-100">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-green-100 mb-6">
                            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Harika İş!</h3>
                        <p class="text-gray-500 text-lg">Şu an istasyonunda bekleyen herhangi bir iş emri bulunmuyor.</p>
                        <p class="text-gray-400 text-sm mt-2">Yeni bir iş gelene kadar kahveni yudumlayabilirsin. ☕️</p>
                    </div>
                <?php endif; ?>
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
<?php endif; ?><?php /**PATH /Users/edizguven/Desktop/KaleSavunma/public_html/resources/views/operator/index.blade.php ENDPATH**/ ?>