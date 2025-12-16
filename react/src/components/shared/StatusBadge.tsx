import { cn } from "@/lib/utils";
import { RequestStatus, ReclamationStatus, statusLabels, reclamationStatusLabels } from "@/types";
import { Clock, CheckCircle2, XCircle, Loader2 } from "lucide-react";

interface StatusBadgeProps {
  status: RequestStatus | ReclamationStatus;
  type?: "request" | "reclamation";
}

export function StatusBadge({ status, type = "request" }: StatusBadgeProps) {
  const labels = type === "request" ? statusLabels : reclamationStatusLabels;
  const label = labels[status as keyof typeof labels] || status;

  const configs: Record<string, { className: string; icon: React.ReactNode }> = {
    en_attente: {
      className: "bg-warning/10 text-warning border-warning/20",
      icon: <Clock className="h-3.5 w-3.5" />,
    },
    en_cours: {
      className: "bg-info/10 text-info border-info/20",
      icon: <Loader2 className="h-3.5 w-3.5 animate-spin" />,
    },
    validee: {
      className: "bg-success/10 text-success border-success/20",
      icon: <CheckCircle2 className="h-3.5 w-3.5" />,
    },
    rejetee: {
      className: "bg-destructive/10 text-destructive border-destructive/20",
      icon: <XCircle className="h-3.5 w-3.5" />,
    },
    non_traitee: {
      className: "bg-warning/10 text-warning border-warning/20",
      icon: <Clock className="h-3.5 w-3.5" />,
    },
    traitee: {
      className: "bg-success/10 text-success border-success/20",
      icon: <CheckCircle2 className="h-3.5 w-3.5" />,
    },
  };

  const config = configs[status] || configs.en_attente;

  return (
    <span
      className={cn(
        "inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-medium",
        config.className
      )}
    >
      {config.icon}
      {label}
    </span>
  );
}
