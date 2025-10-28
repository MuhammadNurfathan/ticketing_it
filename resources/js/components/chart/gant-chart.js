import Gantt from 'frappe-gantt';

document.addEventListener("DOMContentLoaded", async () => {
    console.log("✅ GanttChart component loaded");

    const ganttContainer = document.querySelector("#ganttChart");
    if (!ganttContainer) {
        console.error("❌ Gantt container not found");
        return;
    }

    try {
        const response = await fetch("/api/ProjectMonitorGraph");
        if (!response.ok) throw new Error("Failed to fetch project data");
        const projects = await response.json();

        console.log("📦 Data from API:", projects);

        if (!projects.length) {
            ganttContainer.innerHTML = `<p class="text-gray-400 text-center mt-10">Tidak ada data proyek.</p>`;
            return;
        }

        // Convert data ke format yang dimengerti Frappe Gantt
        const tasks = projects.map(p => ({
            id: p.id,
            name: p.name,
            start: p.start,
            end: p.end,
            progress: p.progress,
            custom_class: p.progress >= 80 ? "bar-success" : "bar-progress"
        }));

        // Render Gantt Chart
        const gantt = new Gantt("#ganttChart", tasks, {
            view_mode: 'Week',
            date_format: 'YYYY-MM-DD',
            bar_height: 24,
            padding: 20,
            custom_popup_html: (task) => {
                return `
                    <div class="p-2 text-sm bg-gray-800 text-white rounded">
                        <strong>${task.name}</strong><br>
                        Start: ${task.start}<br>
                        End: ${task.end}<br>
                        Progress: ${task.progress}%
                    </div>
                `;
            }
        });

        console.log("✅ Gantt chart rendered successfully");
    } catch (err) {
        console.error("❌ Error rendering Gantt chart:", err);
        ganttContainer.innerHTML = `<p class="text-red-500 text-center mt-10">Gagal memuat data Gantt Chart.</p>`;
    }
});
