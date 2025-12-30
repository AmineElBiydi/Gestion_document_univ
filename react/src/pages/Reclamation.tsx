import { useState } from "react";
import { useSearchParams, useNavigate } from "react-router-dom";
import { Layout } from "@/components/layout/Layout";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { toast } from "sonner";
import { ReclamationType, reclamationTypeLabels } from "@/types";
import { apiEndpoints } from "@/lib/api";
import {
  MessageSquare,
  Send,
  Upload,
  AlertCircle,
  FileText,
  X,
} from "lucide-react";

export default function Reclamation() {
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();
  const [isLoading, setIsLoading] = useState(false);

  const [requestNumber, setRequestNumber] = useState(searchParams.get("demande") || "");
  const [reclamationType, setReclamationType] = useState<ReclamationType | "">("");
  const [description, setDescription] = useState("");
  const [attachment, setAttachment] = useState<File | null>(null);

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      if (file.size > 5 * 1024 * 1024) {
        toast.error("Le fichier ne doit pas dépasser 5 Mo");
        return;
      }
      setAttachment(file);
    }
  };

  const removeAttachment = () => {
    setAttachment(null);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    if (!requestNumber || !reclamationType || !description) {
      toast.error("Veuillez remplir tous les champs obligatoires");
      return;
    }

    setIsLoading(true);

    try {
      const formData = new FormData();
      formData.append("num_demande", requestNumber);
      formData.append("type", reclamationType);
      formData.append("description", description);
      if (attachment) {
        formData.append("piece_jointe", attachment);
      }

      const response = await apiEndpoints.createReclamation(formData);

      if (response.data.success) {
        toast.success("Réclamation envoyée avec succès !");
        navigate("/suivi");
      }
    } catch (error: any) {
      console.error("Reclamation error:", error);
      toast.error(error.response?.data?.message || "Une erreur est survenue lors de l'envoi");
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <Layout>
      <div className="min-h-[calc(100vh-4rem)] bg-gradient-to-b from-background to-accent/20 py-8 lg:py-12">
        <div className="container max-w-2xl">
          {/* Header */}
          <div className="text-center mb-8">
            <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-warning/10">
              <MessageSquare className="h-8 w-8 text-warning" />
            </div>
            <h1 className="text-3xl font-bold text-foreground mb-2">
              Soumettre une réclamation
            </h1>
            <p className="text-muted-foreground">
              Un problème avec votre demande ? Faites-nous savoir.
            </p>
          </div>

          {/* Form Card */}
          <form onSubmit={handleSubmit}>
            <div className="rounded-2xl border border-border bg-card p-6 lg:p-8 shadow-lg space-y-6">
              {/* Alert */}
              <div className="flex items-start gap-3 p-4 rounded-lg bg-info/10 border border-info/20">
                <AlertCircle className="h-5 w-5 text-info shrink-0 mt-0.5" />
                <div className="text-sm">
                  <p className="font-medium text-info">Information</p>
                  <p className="text-muted-foreground">
                    Votre réclamation sera traitée dans les meilleurs délais.
                    Vous recevrez un email de confirmation.
                  </p>
                </div>
              </div>

              {/* Request Number */}
              <div className="space-y-2">
                <Label htmlFor="requestNumber">
                  Numéro de demande concernée <span className="text-destructive">*</span>
                </Label>
                <Input
                  id="requestNumber"
                  value={requestNumber}
                  onChange={(e) => setRequestNumber(e.target.value)}
                  placeholder="Ex: DMD-2024-0001"
                />
              </div>

              {/* Reclamation Type */}
              <div className="space-y-2">
                <Label htmlFor="type">
                  Objet de la réclamation <span className="text-destructive">*</span>
                </Label>
                <Select
                  value={reclamationType}
                  onValueChange={(v) => setReclamationType(v as ReclamationType)}
                >
                  <SelectTrigger>
                    <SelectValue placeholder="Sélectionnez un objet" />
                  </SelectTrigger>
                  <SelectContent>
                    {(Object.keys(reclamationTypeLabels) as ReclamationType[]).map(
                      (type) => (
                        <SelectItem key={type} value={type}>
                          {reclamationTypeLabels[type]}
                        </SelectItem>
                      )
                    )}
                  </SelectContent>
                </Select>
              </div>

              {/* Description */}
              <div className="space-y-2">
                <Label htmlFor="description">
                  Description détaillée <span className="text-destructive">*</span>
                </Label>
                <Textarea
                  id="description"
                  value={description}
                  onChange={(e) => setDescription(e.target.value)}
                  placeholder="Décrivez votre problème en détail..."
                  rows={5}
                  className="resize-none"
                />
              </div>

              {/* File Upload */}
              <div className="space-y-2">
                <Label>Pièce jointe (optionnel)</Label>
                {!attachment ? (
                  <label className="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-border rounded-xl cursor-pointer hover:border-primary/50 hover:bg-accent/50 transition-all">
                    <div className="flex flex-col items-center justify-center pt-5 pb-6">
                      <Upload className="h-8 w-8 text-muted-foreground mb-2" />
                      <p className="text-sm text-muted-foreground">
                        <span className="font-semibold text-primary">Cliquez pour ajouter</span>
                        {" "}ou glissez-déposez
                      </p>
                      <p className="text-xs text-muted-foreground mt-1">
                        PNG, JPG, PDF (max 5 Mo)
                      </p>
                    </div>
                    <input
                      type="file"
                      className="hidden"
                      accept=".png,.jpg,.jpeg,.pdf"
                      onChange={handleFileChange}
                    />
                  </label>
                ) : (
                  <div className="flex items-center justify-between p-4 rounded-xl bg-muted/50 border border-border">
                    <div className="flex items-center gap-3">
                      <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                        <FileText className="h-5 w-5 text-primary" />
                      </div>
                      <div>
                        <p className="text-sm font-medium text-foreground truncate max-w-[200px]">
                          {attachment.name}
                        </p>
                        <p className="text-xs text-muted-foreground">
                          {(attachment.size / 1024).toFixed(1)} Ko
                        </p>
                      </div>
                    </div>
                    <Button
                      type="button"
                      variant="ghost"
                      size="icon"
                      onClick={removeAttachment}
                    >
                      <X className="h-4 w-4" />
                    </Button>
                  </div>
                )}
              </div>

              {/* Submit Button */}
              <Button
                type="submit"
                className="w-full"
                size="lg"
                disabled={isLoading}
              >
                {isLoading ? (
                  "Envoi en cours..."
                ) : (
                  <>
                    <Send className="mr-2 h-4 w-4" />
                    Envoyer la réclamation
                  </>
                )}
              </Button>
            </div>
          </form>
        </div>
      </div>
    </Layout>
  );
}
