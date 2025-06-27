$(function() {
    // تفعيل Select2 لتحسين القوائم المنسدلة
    $('#report_type_id, #urgency_level, #reporter_type, #observer_id, #operation_area_id, #assigned_to').select2({
        theme: 'bootstrap-5',
        width: '100%',
        dir: 'rtl'
    });
    
    // إظهار/إخفاء حقل الراصد حسب نوع المُبلغ
    $('#reporter_type').on('change', function() {
        const observerContainer = $('#observerSelectContainer');
        const observerSelect = $('#observer_id');
        
        if ($(this).val() === 'observer') {
            observerContainer.show();
            observerSelect.prop('required', true);
        } else {
            observerContainer.hide();
            observerSelect.prop('required', false);
        }
    });
    
    // تحميل نقاط الاستجابة بناءً على منطقة العمليات المختارة
    $('#operation_area_id').on('change', function() {
        const areaId = $(this).val();
        const assignedToSelect = $('#assigned_to');
        
        // إفراغ القائمة الحالية
        assignedToSelect.empty().append('<option value="">اختر نقطة الاستجابة</option>');
        
        if (!areaId) return;
        
        // إظهار مؤشر التحميل
        assignedToSelect.attr('disabled', true);
        
        // جلب نقاط الاستجابة المرتبطة بالمنطقة
        $.ajax({
            url: `/api/operation-areas/${areaId}/response-points`,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // إضافة الخيارات إلى القائمة المنسدلة
                if (data && data.length > 0) {
                    data.forEach(function(point) {
                        assignedToSelect.append(new Option(
                            `${point.name} (${point.code})`, 
                            point.id
                        ));
                    });
                }
                // إعادة تمكين القائمة
                assignedToSelect.attr('disabled', false);
                // تحديث Select2
                assignedToSelect.trigger('change');
            },
            error: function() {
                // إظهار رسالة خطأ
                toastr.error('حدث خطأ أثناء تحميل نقاط الاستجابة');
                // إعادة تمكين القائمة
                assignedToSelect.attr('disabled', false);
            }
        });
    });
    
    // معاينة المرفقات المحددة
    $('#attachments').on('change', function() {
        const files = this.files;
        const previewContainer = $('#attachmentsPreview');
        
        // مسح المعاينات السابقة
        previewContainer.empty();
        
        // إضافة معاينة لكل ملف
        Array.from(files).forEach(function(file, index) {
            let icon = 'ti-file';
            let bgColor = 'bg-label-secondary';
            
            // تحديد الأيقونة حسب نوع الملف
            if (file.type.includes('image')) {
                icon = 'ti-photo';
                bgColor = 'bg-label-primary';
            } else if (file.type.includes('pdf')) {
                icon = 'ti-file-text';
                bgColor = 'bg-label-danger';
            } else if (file.type.includes('word')) {
                icon = 'ti-file-text';
                bgColor = 'bg-label-info';
            } else if (file.type.includes('sheet') || file.type.includes('excel')) {
                icon = 'ti-table';
                bgColor = 'bg-label-success';
            }
            
            // إنشاء عنصر المعاينة
            const previewItem = $(`
                <div class="attachment-preview ${bgColor} rounded p-2 d-flex align-items-center">
                    <i class="ti ${icon} me-2"></i>
                    <span class="small fw-semibold">${file.name}</span>
                </div>
            `);
            
            // إضافة عنصر المعاينة إلى الحاوية
            previewContainer.append(previewItem);
        });
    });
    
    // تشغيل الدوال عند تحميل الصفحة إذا كانت هناك قيم محددة مسبقًا
    if ($('#reporter_type').val() === 'observer') {
        $('#observerSelectContainer').show();
    }
    
    // تحميل نقاط الاستجابة إذا كانت منطقة العمليات محددة مسبقًا
    const areaIdInitial = $('#operation_area_id').val();
    if (areaIdInitial) {
        $('#operation_area_id').trigger('change');
    }
});
