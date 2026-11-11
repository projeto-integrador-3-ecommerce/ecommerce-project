const SUPABASE_ANON_KEY = "sb_publishable_IY9vbvhZZt_McWnvmA34mg_1DlXKeMd";
const SUPABASE_URL = document.querySelector("[data-supabase-url]")?.dataset.supabaseUrl;

if (SUPABASE_URL) {
    window.supabaseClient = window.supabase.createClient(
        SUPABASE_URL,
        SUPABASE_ANON_KEY
    );
}

document.querySelectorAll("[data-supabase-image-upload]").forEach((form) => {
    const fileInput = form.querySelector('input[type="file"][data-supabase-folder]');
    const pathInput = form.querySelector('input[name="image_path"]');
    const status = form.querySelector("[data-supabase-upload-status]");

    if (!fileInput || !pathInput || !status) {
        return;
    }

    let uploadInProgress = false;

    form.addEventListener("submit", async (event) => {
        const file = fileInput.files?.[0];

        if (!file || form.dataset.supabaseUploaded === "true" || !window.supabaseClient) {
            return;
        }

        event.preventDefault();

        if (uploadInProgress) {
            return;
        }

        uploadInProgress = true;
        status.textContent = "Enviando imagem...";
        status.setAttribute("aria-live", "polite");

        try {
            const formData = new FormData();
            formData.append("image", file);
            formData.append("folder", fileInput.dataset.supabaseFolder);

            const response = await fetch(form.dataset.uploadUrl, {
                method: "POST",
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value,
                },
                body: formData,
            });
            const result = await response.json();

            if (!response.ok) {
                throw new Error(result.message || "Não foi possível enviar a imagem.");
            }

            const bucket = form.dataset.supabaseBucket;
            const path = result.path.replace(/^supabase:/, "");
            const { data, error } = window.supabaseClient.storage.from(bucket).getPublicUrl(path);

            if (error) {
                throw error;
            }

            pathInput.value = result.path;
            const preview = form.querySelector("[data-image-preview]");

            if (preview) {
                preview.src = data.publicUrl;
            }

            fileInput.disabled = true;
            form.dataset.supabaseUploaded = "true";
            status.textContent = "Imagem enviada. Salvando...";

            if (event.submitter) {
                form.requestSubmit(event.submitter);
            } else {
                form.requestSubmit();
            }
        } catch (error) {
            status.textContent = error.message || "Não foi possível enviar a imagem.";
        } finally {
            uploadInProgress = false;
        }
    });
});
