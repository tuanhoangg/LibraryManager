<template lang="">
    <div
        class="modal fade"
        id="import-book-modal"
        style="display: none"
        aria-hidden="true"
    >
        <form @submit.prevent="uploadFile">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Thêm người dùng bằng excel</h4>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input
                                type="file"
                                class="form-control"
                                @change="handleFileUpload"
                                id="file"
                                required
                            />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                        >
                            Trở lại
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Lưu
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
export default {
    props: {},
    data() {
        return {
            file: null,
            successMessage: null,
        };
    },
    methods: {
        handleFileUpload(event) {
            this.file = event.target.files[0];
        },
        async uploadFile() {
            this.$emit("loading", true);

            if (!this.file) {
                alert("Please select a file first.");
                this.$emit("loading", false);
                return;
            }

            const formData = new FormData();
            formData.append("file", this.file);

            try {
                const response = await axios.post("/books/import", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                });
                console.log("response", response);
                const jobId = response.data.jobId; // Assuming the response includes a jobId

                // Check the status of the job
                this.pollJobStatus(jobId);
            } catch (error) {
                console.error("Error uploading file:", error.response.data);
                toastr.error(
                    error.response.data.message || "An error occurred"
                );
                this.$emit("loading", false);
            }
        },

        async pollJobStatus(jobId) {
            try {
                const response = await axios.get(
                    `/books/import-status/${jobId}`
                );
                const data = response.data;

                if (data.status === "success") {
                    toastr.success(data.message);
                    // this.$emit('loading', false);
                    $("#import-user-modal").modal("hide");
                    this.$emit("loadItems");
                } else if (data.status === "error") {
                    toastr.error(data.message);
                    this.$emit("loading", false);
                } else {
                    setTimeout(() => this.pollJobStatus(jobId), 5000); // Check again after 5 seconds
                }
            } catch (error) {
                console.error("Error checking job status:", error);
                toastr.error("Có lỗi trong quá trình xử lý");
            }
        },
    },
};
</script>
<style lang=""></style>
