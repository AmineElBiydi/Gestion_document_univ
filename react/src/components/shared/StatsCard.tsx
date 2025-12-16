import { cn } from "@/lib/utils";
import { LucideIcon } from "lucide-react";

interface StatsCardProps {
  title: string;
  value: string | number;
  description?: string;
  icon: LucideIcon;
  trend?: {
    value: number;
    isPositive: boolean;
  };
  variant?: "default" | "primary" | "success" | "warning" | "destructive";
}

export function StatsCard({
  title,
  value,
  description,
  icon: Icon,
  trend,
  variant = "default",
}: StatsCardProps) {
  const variants = {
    default: "bg-card",
    primary: "bg-primary/5 border-primary/20",
    success: "bg-success/5 border-success/20",
    warning: "bg-warning/5 border-warning/20",
    destructive: "bg-destructive/5 border-destructive/20",
  };

  const iconVariants = {
    default: "bg-muted text-muted-foreground",
    primary: "bg-primary/10 text-primary",
    success: "bg-success/10 text-success",
    warning: "bg-warning/10 text-warning",
    destructive: "bg-destructive/10 text-destructive",
  };

  return (
    <div
      className={cn(
        "rounded-xl border p-6 shadow-sm transition-all duration-200 hover:shadow-md",
        variants[variant]
      )}
    >
      <div className="flex items-start justify-between">
        <div className="space-y-1">
          <p className="text-sm font-medium text-muted-foreground">{title}</p>
          <p className="text-3xl font-bold text-foreground">{value}</p>
          {description && (
            <p className="text-xs text-muted-foreground">{description}</p>
          )}
        </div>
        <div
          className={cn(
            "flex h-12 w-12 items-center justify-center rounded-xl",
            iconVariants[variant]
          )}
        >
          <Icon className="h-6 w-6" />
        </div>
      </div>
      {trend && (
        <div className="mt-4 flex items-center gap-1 text-xs">
          <span
            className={cn(
              "font-medium",
              trend.isPositive ? "text-success" : "text-destructive"
            )}
          >
            {trend.isPositive ? "+" : "-"}{Math.abs(trend.value)}%
          </span>
          <span className="text-muted-foreground">vs mois dernier</span>
        </div>
      )}
    </div>
  );
}
