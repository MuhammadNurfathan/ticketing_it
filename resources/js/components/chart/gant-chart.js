import Gantt from "frappe-gantt";
import "frappe-gantt/dist/frappe-gantt.css"; // import CSS agar style muncul


document.addEventListener("DOMContentLoaded", async () => {
    console.log("✅ GanttChart component loaded");

    const ganttContainer = document.querySelector("#ganttChart");
    if (!ganttContainer) {
        console.error("❌ Gantt container not found");
        return;
    }

    // Deteksi Tailwind dark mode
    const isDark = document.documentElement.classList.contains('dark');

    try {
        const response = await fetch("/api/ProjectMonitorGraph");
        if (!response.ok) throw new Error("Failed to fetch project data");
        const projects = await response.json();

        console.log("📦 Data from API:", projects);

        if (!projects.length) {
            ganttContainer.innerHTML = `<p class="text-gray-400 text-center mt-10">Tidak ada data proyek.</p>`;
            return;
        }

        // Convert data ke format Frappe Gantt
        const tasks = projects.map(p => ({
            id: p.id,
            name: p.name,
            start: p.start,
            end: p.end,
            progress: p.progress,
            custom_class: p.progress >= 100 ? "bar-success" : "bar-progress"
        }));

        // Render Gantt Chart dengan warna dark/light
        const gantt = new Gantt("#ganttChart", tasks, {
            view_mode: 'Week',
            date_format: 'YYYY-MM-DD',
            bar_height: 24,
            padding: 20,
            bar_color: isDark ? "#2A2F42" : "#F0F2F5",           // bar utama
            bar_progress_color: isDark ? "#22C55E" : "#3B82F6",   // progress
            font_color: isDark ? "#FFFFFF" : "#000000",           // teks
            grid_color: isDark ? "#374151" : "#d1d5db",           // grid lines
            custom_popup_html: (task) => `
                <div class="p-2 text-sm rounded ${isDark ? 'bg-dark-eval-2 text-white' : 'bg-light-eval-3 text-black'}">
                    <strong>${task.name}</strong><br>
                    Start: ${task.start}<br>
                    End: ${task.end}<br>
                    Progress: ${task.progress}%
                </div>
            `
        });

        console.log("✅ Gantt chart rendered successfully");
    } catch (err) {
        console.error("❌ Error rendering Gantt chart:", err);
        ganttContainer.innerHTML = `<p class="text-red-500 text-center mt-10">Gagal memuat data Gantt Chart.</p>`;
    }
});
