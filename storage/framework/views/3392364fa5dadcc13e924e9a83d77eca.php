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
            <?php echo e(__('İş Emri Yönetim Merkezi')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <?php if(session('success')): ?>
                <div class="mb-6 p-4 bg-green-600 text-white rounded-lg shadow-lg font-bold flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-md">
                    <p class="font-bold">Dikkat! Kayıt yapılamadı:</p>
                    <ul class="list-disc pl-5 mt-2">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="bg-white shadow-xl rounded-xl p-8 mb-8 border border-gray-200">
                <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-4">Yeni İş Emri Tanımla</h3>

                <form action="<?php echo e(route('admin.jobs.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">İŞ BAŞLIĞI</label>
                        <input type="text" name="title" value="<?php echo e(old('title')); ?>" required 
                               class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Örn: Üretim Projesi A">
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm font-bold"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="p-4 border-2 border-dashed border-blue-300 rounded bg-blue-50 hover:bg-blue-100 transition">
                            <label class="block text-blue-800 font-bold mb-2">1. DOSYA (Görsel, DWG, vb.)</label>
                            <input type="file" name="image" required class="w-full text-sm text-gray-600">
                            <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm font-bold block mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="p-4 border-2 border-dashed border-red-300 rounded bg-red-50 hover:bg-red-100 transition">
                            <label class="block text-red-800 font-bold mb-2">2. DOSYA (Teknik Çizim, Excel, vb.)</label>
                            <input type="file" name="pdf" required class="w-full text-sm text-gray-600">
                            <?php $__errorArgs = ['pdf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm font-bold block mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="bg-gray-50 border-2 border-indigo-200 rounded-xl p-6 shadow-inner">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-indigo-800 font-bold uppercase tracking-wider text-sm flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                                İşlem Kontrol Paneli
                            </span>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-4 justify-end">
                            <button type="reset" class="px-6 py-4 bg-white text-gray-600 font-bold rounded-lg border border-gray-300 hover:bg-gray-100 hover:text-red-500 transition shadow-sm">
                                FORMU TEMİZLE
                            </button>

                            <button type="submit" class="px-8 py-4 bg-indigo-600 text-white font-black rounded-lg shadow-lg hover:bg-indigo-700 hover:shadow-xl transform hover:-translate-y-1 transition duration-200 w-full md:w-auto text-center">
                                KAYDET VE BAŞLAT
                            </button>
                        </div>
                    </div>

                </form>
            </div>

            <div class="bg-white shadow-lg rounded-xl overflow-hidden p-6">
                <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">Mevcut İşler Listesi (<?php echo e(count($jobs)); ?>)</h3>
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 text-sm uppercase">
                            <th class="p-4">İş No</th>
                            <th class="p-4">Başlık</th>
                            <th class="p-4">Dosyalar</th>
                            <th class="p-4">Durum</th>
                            <th class="p-4 text-right">İŞLEMLER</th> </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="p-4 text-indigo-600 font-bold font-mono"><?php echo e($job->job_no); ?></td>
                                <td class="p-4 font-medium"><?php echo e($job->title); ?></td>
                                <td class="p-4">
                                    <?php if($job->image_path): ?>
                                        <a href="<?php echo e(asset('storage/' . $job->image_path)); ?>" target="_blank" class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded text-xs font-bold mr-2 hover:bg-blue-200">Resim</a>
                                    <?php else: ?>
                                        <span class="inline-block px-3 py-1 bg-gray-100 text-gray-500 rounded text-xs">Resim Yok</span>
                                    <?php endif; ?>
                                    <?php if($job->pdf_path): ?>
                                        <a href="<?php echo e(asset('storage/' . $job->pdf_path)); ?>" target="_blank" class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded text-xs font-bold hover:bg-red-200">PDF</a>
                                    <?php else: ?>
                                        <span class="inline-block px-3 py-1 bg-gray-100 text-gray-500 rounded text-xs">PDF Yok</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4">
                                    <span class="px-3 py-1 rounded text-xs font-bold
                                        <?php echo e($job->is_completed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                        <?php echo e($job->is_completed ? 'Tamamlandı' : strtoupper($job->current_stage)); ?>

                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <a href="<?php echo e(route('admin.jobs.show', $job->id)); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 shadow-md transition transform hover:scale-105">
                                        DETAY / RAPOR 🔍
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-500 bg-gray-50 rounded italic">Henüz aktif bir iş emri bulunmuyor.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
<?php endif; ?><?php /**PATH /Users/edizguven/Desktop/total/KaleSavunma/resources/views/admin/jobs/index.blade.php ENDPATH**/ ?>