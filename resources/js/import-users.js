export default () => ({
    step: 1,
    importType: 'staf',
    file: null,
    fileName: '',
    isDragging: false,
    rows: [],
    isProcessing: false,
    isSubmitting: false,
    errorMessage: '',
    successMessage: '',

    handleDrop(e) {
        this.isDragging = false;
        const files = e.dataTransfer.files;
        if (files.length) {
            this.processFile(files[0]);
        }
    },

    handleFileSelect(e) {
        const files = e.target.files;
        if (files.length) {
            this.processFile(files[0]);
        }
    },

    processFile(file) {
        if (!file.name.match(/\.(xlsx|csv)$/i)) {
            this.errorMessage = 'Format file tidak didukung. Harap gunakan .xlsx atau .csv';
            return;
        }
        this.errorMessage = '';
        this.file = file;
        this.fileName = file.name;
        this.step = 2;
        this.extractData();
    },

    extractData() {
        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const data = e.target.result;
                const workbook = window.XLSX.read(data, { type: 'binary' });
                const firstSheetName = workbook.SheetNames[0];
                const worksheet = workbook.Sheets[firstSheetName];

                const jsonData = window.XLSX.utils.sheet_to_json(worksheet, { header: 1 });

                let parsedRows = [];
                for (let i = 1; i < jsonData.length; i++) {
                    const row = jsonData[i];
                    if (!row || row.length === 0 || (!row[0] && !row[1])) continue;

                    let newRow = {
                        id: 'row_' + i,
                        editing: false,
                        isValidating: false,
                        errors: {},
                        isValid: false,
                    };

                    if (this.importType === 'staf') {
                        newRow.name = row[0] || '';
                        newRow.email = row[1] || '';
                        newRow.phone_number = row[2] || '';
                        newRow.nip = row[3] || '';
                        newRow.department_code = row[4] || '';
                        newRow.role = row[5] || '';
                    } else {
                        newRow.name = row[0] || '';
                        newRow.email = row[1] || '';
                        newRow.phone_number = row[2] || '';
                        newRow.nim = row[3] || '';
                        newRow.department_code = row[4] || '';
                        newRow.entry_year = row[5] || '';
                        newRow.role = row[6] || '';
                        newRow.supervisor_nip = row[7] || '';
                    }
                    parsedRows.push(newRow);
                }

                this.rows = parsedRows;
                this.validateBulk();
            } catch (err) {
                console.error(err);
                this.errorMessage = 'Terjadi kesalahan saat membaca file Excel. Pastikan format sesuai template.';
                this.step = 1;
            }
        };
        reader.readAsBinaryString(this.file);
    },

    async validateBulk() {
        this.isProcessing = true;
        try {
            const response = await fetch('/admin/users/validate-bulk', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    type: this.importType,
                    rows: this.rows
                })
            });

            const result = await response.json();
            if (result.success && result.data) {
                this.rows = result.data;
            }
        } catch (error) {
            console.error('Validation error:', error);
            this.errorMessage = 'Gagal melakukan validasi ke server.';
        } finally {
            this.isProcessing = false;
            this.step = 3;
        }
    },

    async validateRow(index) {
        const row = this.rows[index];
        row.isValidating = true;
        row.editing = false;

        try {
            const response = await fetch('/admin/users/validate-row', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    type: this.importType,
                    row: row
                })
            });

            const result = await response.json();
            if (result.success) {
                this.rows[index] = result.data;
            }
        } catch (error) {
            console.error('Row validation error:', error);
        } finally {
            this.rows[index].isValidating = false;
        }
    },

    deleteRow(index) {
        this.rows.splice(index, 1);
    },

    get allValid() {
        return this.rows.length > 0 && this.rows.every(r => r.isValid);
    },

    get invalidCount() {
        return this.rows.filter(r => !r.isValid).length;
    },

    async submitBulk() {
        if (!this.allValid) return;
        this.isSubmitting = true;

        try {
            const response = await fetch('/admin/users/process-bulk', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    type: this.importType,
                    rows: this.rows
                })
            });

            const result = await response.json();
            if (result.success) {
                // Redirect with session flash via window location
                window.location.href = '/admin/users?bulk_import_success=true';
            } else {
                this.errorMessage = result.message || 'Gagal memproses import.';
                this.isSubmitting = false;
            }
        } catch (error) {
            console.error('Submit error:', error);
            this.errorMessage = 'Terjadi kesalahan jaringan saat mengirim data.';
            this.isSubmitting = false;
        }
    }
});
