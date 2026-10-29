document.addEventListener('alpine:init', () => {
    Alpine.data('imageCropper', (config) => ({
        name: config.name,
        showModal: false,
        initialSrc: config.src || '',
        preview: config.src || '',
        croppedImage: '',
        error: '',
        cropper: null,
        aspectRatioValue: null,
        minWidth: config.minWidth || 0,
        minHeight: config.minHeight || 0,
        maxWidth: config.maxWidth || 2000,
        maxHeight: config.maxHeight || 2000,
        uploadUrl: config.uploadUrl || '',
        csrfToken: config.csrfToken || '',
        required: config.required || false,
        maxScale: config.maxScale !== undefined ? config.maxScale : 512,
        tempImageData: null,
        disableCrop: config.disableCrop || false,

        init() {
            const aspectRatio = config.aspectRatio || null;
            if (aspectRatio && aspectRatio.includes(':')) {
                const [width, height] = aspectRatio.split(':').map(Number);
                if (!isNaN(width) && !isNaN(height) && height !== 0) {
                    this.aspectRatioValue = width / height;
                }
            } else if (aspectRatio && !isNaN(aspectRatio)) {
                this.aspectRatioValue = Number(aspectRatio);
            }
        },

        selectFile() {
            this.$refs.fileInput.click();
        },

        handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;

            this.error = '';

            const currentFile = file;
            this.$refs.fileInput.value = '';

            if (currentFile.size > 5 * 1024 * 1024) {
                this.error = 'File size exceeds 5MB limit';
                return;
            }

            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!validTypes.includes(currentFile.type)) {
                this.error = 'Invalid file type. Please use JPG, PNG, GIF, or WebP images.';
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    if (img.width < this.minWidth || img.height < this.minHeight) {
                        this.error = `Image dimensions (${img.width}x${img.height}px) too small. Minimum size is ${this.minWidth}x${this.minHeight}px`;
                        return;
                    }

                    if (img.width > this.maxWidth || img.height > this.maxHeight) {
                        this.error = `Image dimensions (${img.width}x${img.height}px) too large. Maximum size is ${this.maxWidth}x${this.maxHeight}px`;
                        return;
                    }

                    this.tempImageData = e.target.result;
                    if (this.disableCrop) {
                        if (
                            this.maxScale > 0 &&
                            (img.width > this.maxScale || img.height > this.maxScale)
                        ) {
                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');

                            let newWidth, newHeight;
                            if (img.width > img.height) {
                                newWidth = this.maxScale;
                                newHeight = Math.round((img.height * this.maxScale) / img.width);
                            } else {
                                newHeight = this.maxScale;
                                newWidth = Math.round((img.width * this.maxScale) / img.height);
                            }

                            canvas.width = newWidth;
                            canvas.height = newHeight;
                            ctx.drawImage(
                                img,
                                0,
                                0,
                                img.width,
                                img.height,
                                0,
                                0,
                                newWidth,
                                newHeight,
                            );

                            const scaledImage = canvas.toDataURL('image/png');
                            this.preview = scaledImage;
                            this.croppedImage = scaledImage;

                            this.$dispatch('image-cropped', {
                                name: this.name,
                                data: scaledImage,
                            });
                        } else {
                            this.preview = e.target.result;
                            this.croppedImage = e.target.result;

                            this.$dispatch('image-cropped', {
                                name: this.name,
                                data: e.target.result,
                            });
                        }
                    } else {
                        this.openCropModal();
                    }
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(currentFile);
        },

        openCropModal() {
            if (!this.tempImageData) {
                if (!this.preview || this.preview === this.initialSrc) {
                    this.error = 'No image selected for cropping';
                    return;
                }
                this.tempImageData = this.preview;
            }

            this.showModal = true;
            this.$nextTick(() => {
                if (this.$refs.cropperImage) {
                    this.$refs.cropperImage.src = this.tempImageData;
                    this.initCropper();
                }
            });
        },

        initCropper() {
            this.destroyCropper();

            try {
                this.cropper = new Cropper(this.$refs.cropperImage, {
                    viewMode: 1,
                    aspectRatio: this.aspectRatioValue,
                    autoCropArea: 0.8,
                    responsive: true,
                    restore: false,
                    center: true,
                    highlight: false,
                    guides: true,
                    background: false,
                    crop: (event) => {
                        let width = event.detail.width;
                        let height = event.detail.height;

                        if (width < this.minWidth || height < this.minHeight) {
                            this.cropper.setData({
                                width: Math.max(this.minWidth, width),
                                height: Math.max(this.minHeight, height),
                            });
                        }
                    },
                });
            } catch (err) {
                console.error('Failed to initialize cropper:', err);
                this.error = 'Failed to initialize image cropper';
            }
        },

        async applyCrop() {
            if (!this.cropper) {
                this.error = 'Image cropper not initialized';
                return;
            }

            try {
                const canvas = this.cropper.getCroppedCanvas({
                    maxWidth: this.maxWidth,
                    maxHeight: this.maxHeight,
                    imageSmoothingQuality: 'high',
                });

                if (!canvas) {
                    this.error = 'Failed to crop image';
                    return;
                }

                let finalCanvas = canvas;
                if (
                    this.maxScale > 0 &&
                    (canvas.width > this.maxScale || canvas.height > this.maxScale)
                ) {
                    finalCanvas = document.createElement('canvas');
                    const ctx = finalCanvas.getContext('2d');

                    let newWidth, newHeight;
                    if (canvas.width > canvas.height) {
                        newWidth = this.maxScale;
                        newHeight = Math.round((canvas.height * this.maxScale) / canvas.width);
                    } else {
                        newHeight = this.maxScale;
                        newWidth = Math.round((canvas.width * this.maxScale) / canvas.height);
                    }

                    finalCanvas.width = newWidth;
                    finalCanvas.height = newHeight;
                    ctx.drawImage(
                        canvas,
                        0,
                        0,
                        canvas.width,
                        canvas.height,
                        0,
                        0,
                        newWidth,
                        newHeight,
                    );
                }

                const croppedData = finalCanvas.toDataURL('image/png');

                if (this.uploadUrl) {
                    await this.uploadImage(croppedData);
                } else {
                    this.preview = croppedData;
                    this.croppedImage = croppedData;

                    this.$dispatch('image-cropped', {
                        name: this.name,
                        data: croppedData,
                    });
                }

                this.showModal = false;
                this.destroyCropper();
                this.tempImageData = null;
            } catch (err) {
                console.error('Failed to crop image:', err);
                this.error = 'Failed to crop image';
            }
        },

        async uploadImage(imageData) {
            try {
                const response = await fetch(this.uploadUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: JSON.stringify({ image: imageData }),
                });

                if (!response.ok) {
                    throw new Error('Upload failed');
                }

                const data = await response.json();
                this.preview = data.url;
                this.croppedImage = data.path || data.url;

                this.$dispatch('image-cropped', {
                    name: this.name,
                    url: data.url,
                    path: data.path || data.url,
                });
            } catch (error) {
                console.error('Upload failed:', error);
                this.error = 'Failed to upload image. Please try again.';
            }
        },

        cancelCrop() {
            this.showModal = false;
            this.destroyCropper();
            this.tempImageData = null;
        },

        destroyCropper() {
            if (this.cropper) {
                try {
                    this.cropper.destroy();
                } catch (err) {
                    console.error('Error destroying cropper:', err);
                }
                this.cropper = null;
            }
        },
    }));
});
