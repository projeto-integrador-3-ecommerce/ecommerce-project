const SUPABASE_ANON_KEY = "sb_publishable_IY9vbvhZZt_McWnvmA34mg_1DlXKeMd";
const SUPABASE_URL = document.querySelector("[data-supabase-url]")?.dataset.supabaseUrl;

if (SUPABASE_URL && window.supabase) {
    try {
        window.supabaseClient = window.supabase.createClient(
            SUPABASE_URL,
            SUPABASE_ANON_KEY
        );
    } catch (error) {
        console.error("Não foi possível inicializar o cliente Supabase.", error);
    }
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

        if (!file || form.dataset.supabaseUploaded === "true") {
            return;
        }

        event.preventDefault();

        if (uploadInProgress) {
            return;
        }

        if (!SUPABASE_URL || !form.dataset.supabaseBucket || !form.dataset.uploadUrl) {
            status.textContent = "Supabase não configurado. Verifique SUPABASE_URL e SUPABASE_BUCKET no Render.";
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
            const result = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(result.message || `Falha no envio da imagem (HTTP ${response.status}).`);
            }

            const bucket = form.dataset.supabaseBucket;
            const path = result.path.replace(/^supabase:/, "");
            const publicUrl = window.supabaseClient
                ? window.supabaseClient.storage.from(bucket).getPublicUrl(path).data.publicUrl
                : `${SUPABASE_URL.replace(/\/+$/, "")}/storage/v1/object/public/${encodeURIComponent(bucket)}/${path.split("/").map(encodeURIComponent).join("/")}`;

            pathInput.value = result.path;
            const preview = form.querySelector("[data-image-preview]");

            if (preview) {
                preview.src = publicUrl;
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
