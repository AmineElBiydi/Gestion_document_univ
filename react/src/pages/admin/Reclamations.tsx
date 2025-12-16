import { useState, useEffect } from "react";
import { useAdminAuth } from "@/hooks/useAdminAuth";
import { Layout } from "@/components/layout/Layout";
import { ReclamationsSkeleton } from "@/components/shared/Skeleton";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { StatusBadge } from "@/components/shared/StatusBadge";
import { toast } from "sonner";
import {
  Reclamation,
  ReclamationStatus,
  reclamationTypeLabels,
  reclamationStatusLabels,
} from "@/types";
import {
  Search,
  MessageSquare,
  Send,
  Eye,
  CheckCircle2,
  Clock,
  FileText,
} from "lucide-react";
import { apiEndpoints } from "@/lib/api";

export default function AdminReclamations() {
  const { isAuthenticated, isLoading } = useAdminAuth();
  const [reclamations, setReclamations] = useState<Reclamation[]>([]);
  const [isLoadingReclamations, setIsLoadingReclamations] = useState(false);
  const [isInitialLoad, setIsInitialLoad] = useState(true);
  const [searchQuery, setSearchQuery] = useState("");
  const [statusFilter, setStatusFilter] = useState<string>("all");

  const [selectedReclamation, setSelectedReclamation] = useState<Reclamation | null>(null);
  const [showViewDialog, setShowViewDialog] = useState(false);
  const [showResponseDialog, setShowResponseDialog] = useState(false);
  const [response, setResponse] = useState("");

  // Load reclamations data from API
  useEffect(() => {
    if (isAuthenticated) {
      loadReclamations();
    }
  }, [isAuthenticated]);

  const loadReclamations = async () => {
    if (!isInitialLoad) {
      setIsLoadingReclamations(true);
    }
    try {
      const response = await apiEndpoints.getReclamations({
        status: statusFilter !== "all" ? statusFilter : undefined,
        search: searchQuery || undefined,
      });
      
      if (response.data.success) {
        const reclamationsData = response.data.data.data;
        
        // Transform API data to frontend format
        const transformedReclamations = reclamationsData.map((reclamation: any) => ({
          id: reclamation.id.toString(),
          requestId: `DMD-${new Date(reclamation.created_at).getFullYear()}-${String(reclamation.demande_id || reclamation.id).padStart(4, '0')}`,
          studentId: reclamation.etudiant_id?.toString() || '',
          studentName: `${reclamation.etudiant?.nom || ''} ${reclamation.etudiant?.prenom || ''}`.trim(),
          studentApogee: reclamation.etudiant?.apogee || '',
          type: reclamation.type as any,
          description: reclamation.description,
          status: reclamation.status as ReclamationStatus,
          response: reclamation.reponse,
          createdAt: reclamation.created_at,
          updatedAt: reclamation.updated_at,
        }));
        
        setReclamations(transformedReclamations);
      }
    } catch (error) {
      console.error("Reclamations loading error:", error);
      setReclamations([]);
    } finally {
      setIsLoadingReclamations(false);
      setIsInitialLoad(false);
    }
  };

  // Reload data when filters change
  useEffect(() => {
    if (isAuthenticated) {
      loadReclamations();
    }
  }, [statusFilter, searchQuery]);

  const handleRespond = async () => {
    if (!selectedReclamation || !response.trim()) {
      toast.error("Veuillez saisir une réponse");
      return;
    }
    
    try {
      const result = await apiEndpoints.repondreReclamation(selectedReclamation.id, { reponse: response });
      
      if (result.data.success) {
        toast.success("Réponse envoyée avec succès");
        setShowResponseDialog(false);
        setSelectedReclamation(null);
        setResponse("");
        
        // Reload reclamations to update the status
        loadReclamations();
      }
    } catch (error) {
      console.error("Response error:", error);
      toast.error("Erreur lors de l'envoi de la réponse");
    }
  };

  const getStatusIcon = (status: ReclamationStatus) => {
    switch (status) {
      case "non_traitee":
        return <Clock className="h-5 w-5 text-warning" />;
      case "en_cours":
        return <MessageSquare className="h-5 w-5 text-info" />;
      case "traitee":
        return <CheckCircle2 className="h-5 w-5 text-success" />;
    }
  };

  if (isLoading || isInitialLoad) {
    return <ReclamationsSkeleton />;
  }

  if (!isAuthenticated) return null;

  return (
    <Layout showFooter={false}>
      <div className="min-h-[calc(100vh-4rem)] bg-muted/30 py-8">
        <div className="container">
          {/* Header */}
          <div className="mb-8">
            <h1 className="text-3xl font-bold text-foreground">Réclamations</h1>
            <p className="text-muted-foreground mt-1">
              Gérez les réclamations des étudiants
            </p>
          </div>

          {/* Stats */}
          <div className="grid gap-4 md:grid-cols-3 mb-8">
            <div className="rounded-xl border border-warning/20 bg-warning/5 p-4">
              <div className="flex items-center gap-3">
                <Clock className="h-8 w-8 text-warning" />
                <div>
                  <p className="text-2xl font-bold text-foreground">
                    {reclamations.filter((r) => r.status === "non_traitee").length}
                  </p>
                  <p className="text-sm text-muted-foreground">Non traitées</p>
                </div>
              </div>
            </div>
            <div className="rounded-xl border border-info/20 bg-info/5 p-4">
              <div className="flex items-center gap-3">
                <MessageSquare className="h-8 w-8 text-info" />
                <div>
                  <p className="text-2xl font-bold text-foreground">
                    {reclamations.filter((r) => r.status === "en_cours").length}
                  </p>
                  <p className="text-sm text-muted-foreground">En cours</p>
                </div>
              </div>
            </div>
            <div className="rounded-xl border border-success/20 bg-success/5 p-4">
              <div className="flex items-center gap-3">
                <CheckCircle2 className="h-8 w-8 text-success" />
                <div>
                  <p className="text-2xl font-bold text-foreground">
                    {reclamations.filter((r) => r.status === "traitee").length}
                  </p>
                  <p className="text-sm text-muted-foreground">Traitées</p>
                </div>
              </div>
            </div>
          </div>

          {/* Filters */}
          <div className="rounded-xl border border-border bg-card p-4 mb-6 shadow-sm">
            <div className="flex flex-col sm:flex-row gap-4">
              <div className="flex-1 relative">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                <Input
                  placeholder="Rechercher par n° demande..."
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  className="pl-10"
                />
              </div>
              <Select value={statusFilter} onValueChange={setStatusFilter}>
                <SelectTrigger className="w-[180px]">
                  <SelectValue placeholder="Statut" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="all">Tous les statuts</SelectItem>
                  <SelectItem value="non_traitee">Non traitée</SelectItem>
                  <SelectItem value="en_cours">En cours</SelectItem>
                  <SelectItem value="traitee">Traitée</SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>

          {/* Reclamations List */}
          <div className="space-y-4">
            {isLoadingReclamations ? (
              <div className="text-center py-12">
                <div className="flex items-center justify-center">
                  <div className="animate-spin h-6 w-6 border-2 border-primary border-t-transparent rounded-full mr-2" />
                  Chargement...
                </div>
              </div>
            ) : reclamations.length === 0 ? (
              <div className="text-center py-12">
                <p className="text-muted-foreground">Aucune réclamation trouvée</p>
              </div>
            ) : (
              reclamations.map((reclamation) => (
                <div
                  key={reclamation.id}
                  className="rounded-xl border border-border bg-card p-6 shadow-sm hover:shadow-md transition-shadow"
                >
                  <div className="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                    <div className="flex gap-4">
                      <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-muted">
                        {getStatusIcon(reclamation.status)}
                      </div>
                      <div className="flex-1">
                        <div className="flex items-center gap-3 mb-2">
                          <h3 className="font-semibold text-foreground">
                            {reclamationTypeLabels[reclamation.type]}
                          </h3>
                          <StatusBadge status={reclamation.status} type="reclamation" />
                        </div>
                        <div className="flex items-center gap-2 text-sm text-muted-foreground mb-3">
                          <FileText className="h-4 w-4" />
                          Demande: {reclamation.requestId}
                          <span className="mx-2">•</span>
                          {new Date(reclamation.createdAt).toLocaleDateString("fr-FR")}
                        </div>
                        <p className="text-sm text-foreground line-clamp-2">
                          {reclamation.description}
                        </p>
                        {(reclamation.studentName || reclamation.studentApogee) && (
                          <div className="mt-2 text-sm text-muted-foreground">
                            Étudiant: {reclamation.studentName || `Apogee: ${reclamation.studentApogee}`}
                          </div>
                        )}
                      </div>
                    </div>
                    <div className="flex items-center gap-2">
                      <Button
                        variant="outline"
                        size="sm"
                        onClick={() => {
                          setSelectedReclamation(reclamation);
                          setShowViewDialog(true);
                        }}
                      >
                        <Eye className="h-4 w-4 mr-1" />
                        Voir
                      </Button>
                      {reclamation.status !== "traitee" && (
                        <Button
                          variant="outline"
                          size="sm"
                          onClick={() => {
                            setSelectedReclamation(reclamation);
                            setShowResponseDialog(true);
                          }}
                        >
                          <Send className="h-4 w-4 mr-1" />
                          Répondre
                        </Button>
                      )}
                    </div>
                  </div>
                </div>
              ))
            )}
          </div>
        </div>
      </div>

      {/* View Dialog */}
      <Dialog open={showViewDialog} onOpenChange={setShowViewDialog}>
        <DialogContent className="max-w-lg">
          <DialogHeader>
            <DialogTitle>Détails de la réclamation</DialogTitle>
            <DialogDescription>
              Demande concernée: {selectedReclamation?.requestId}
            </DialogDescription>
          </DialogHeader>
          {selectedReclamation && (
            <div className="space-y-4">
              <div className="flex items-center justify-between">
                <span className="text-muted-foreground">Type:</span>
                <span className="font-medium">
                  {reclamationTypeLabels[selectedReclamation.type]}
                </span>
              </div>
              <div className="flex items-center justify-between">
                <span className="text-muted-foreground">Statut:</span>
                <StatusBadge status={selectedReclamation.status} type="reclamation" />
              </div>
              <div className="flex items-center justify-between">
                <span className="text-muted-foreground">Date:</span>
                <span className="font-medium">
                  {new Date(selectedReclamation.createdAt).toLocaleDateString("fr-FR")}
                </span>
              </div>
              <div>
                <p className="text-muted-foreground mb-2">Description:</p>
                <p className="text-sm bg-muted/50 p-3 rounded-lg">
                  {selectedReclamation.description}
                </p>
              </div>
              {selectedReclamation.response && (
                <div>
                  <p className="text-muted-foreground mb-2">Réponse:</p>
                  <p className="text-sm bg-success/5 border border-success/20 p-3 rounded-lg">
                    {selectedReclamation.response}
                  </p>
                </div>
              )}
            </div>
          )}
          <DialogFooter>
            <Button variant="outline" onClick={() => setShowViewDialog(false)}>
              Fermer
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {/* Response Dialog */}
      <Dialog open={showResponseDialog} onOpenChange={setShowResponseDialog}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Répondre à la réclamation</DialogTitle>
            <DialogDescription>
              Demande: {selectedReclamation?.requestId}
            </DialogDescription>
          </DialogHeader>
          <div className="space-y-4">
            <div>
              <p className="text-sm text-muted-foreground mb-2">Réclamation:</p>
              <p className="text-sm bg-muted/50 p-3 rounded-lg">
                {selectedReclamation?.description}
              </p>
            </div>
            <div className="space-y-2">
              <Label htmlFor="response">Votre réponse</Label>
              <div className="relative">
                <Textarea
                  id="response"
                  value={response}
                  onChange={(e) => setResponse(e.target.value)}
                  placeholder="Saisissez votre réponse..."
                  rows={5}
                  className={response.length < 10 ? "border-orange-300" : ""}
                />
                <div className="absolute bottom-2 right-2 text-xs">
                  <span className={response.length < 10 ? "text-orange-500 font-medium" : "text-green-600"}>
                    {response.length}/10 caractères
                  </span>
                </div>
              </div>
              {response.length > 0 && response.length < 10 && (
                <p className="text-xs text-orange-600">
                  Veuillez saisir au moins 10 caractères avant d'envoyer
                </p>
              )}
            </div>
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setShowResponseDialog(false)}>
              Annuler
            </Button>
            <Button 
              onClick={handleRespond}
              disabled={response.length < 10}
              className={response.length < 10 ? "opacity-50 cursor-not-allowed" : ""}
            >
              <Send className="mr-2 h-4 w-4" />
              Envoyer la réponse
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </Layout>
  );
}
