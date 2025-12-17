
import { useState } from "react";
import { useNavigate, useLocation } from "react-router-dom";
import { Layout } from "@/components/layout/Layout";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { StatusBadge } from "@/components/shared/StatusBadge";
import { toast } from "sonner";
import {
  Search,
  FileText,
  Calendar,
  Download,
  MessageSquare,
  CheckCircle2,
  Clock,
  User,
  Hash,
  AlertCircle,
} from "lucide-react";
import { DocumentRequest, documentTypeLabels } from "@/types";
import { Link } from "react-router-dom";
import ReleveNotesTemplate from "@/components/ReleveNotesTemplate";
import { cn } from "@/lib/utils";
import { apiEndpoints } from "@/lib/api";

// Mock student database for validation
const MOCK_STUDENTS = [
  { email: "student@universite.ma", apogee: "12345678", cin: "AB123456" },
  { email: "etudiant@universite.ma", apogee: "87654321", cin: "CD987654" },
];

// Mock data for demonstration
const mockRequests: DocumentRequest[] = [
  {
    id: "1",
    requestNumber: "DMD-2024-0001",
    studentId: "123",
    documentType: "attestation_scolaire",
    status: "validee",
    createdAt: new Date("2024-11-15"),
    updatedAt: new Date("2024-11-16"),
    details: { annee: "2024-2025", niveau: "S3" },
    documentUrl: "#",
  },
  {
    id: "2",
    requestNumber: "DMD-2024-0002",
    studentId: "123",
    documentType: "releve_notes",
    status: "en_cours",
    createdAt: new Date("2024-11-20"),
    updatedAt: new Date("2024-11-21"),
    details: { semestre: "S2", annee: "2023-2024", typeReleve: "officiel" },
  },
  {
    id: "3",
    requestNumber: "DMD-2024-0003",
    studentId: "123",
    documentType: "convention_stage",
    status: "en_attente",
    createdAt: new Date("2024-11-28"),
    updatedAt: new Date("2024-11-28"),
    details: { entreprise: "Tech Corp", dateDebut: "2025-01-15", dateFin: "2025-03-15" },
  },
];

export default function Suivi() {
  const navigate = useNavigate();
  const location = useLocation();

  // Request number search state
  const [requestNumberSearch, setRequestNumberSearch] = useState("");
  const [isLoading, setIsLoading] = useState(false);
  const [selectedTranscript, setSelectedTranscript] = useState<DocumentRequest | null>(null);

  const [requests, setRequests] = useState<DocumentRequest[] | null>(null);

  const searchByRequestNumber = async () => {
    setIsLoading(true);
    try {
      const response = await apiEndpoints.suivreDemandes({
        email: "",
        apogee: "",
        cin: "",
        num_demande: requestNumberSearch.trim() || undefined,
      });

      if (response.data.success) {
        const demandes = response.data.data;

        // Filter by request number if provided
        let filteredRequests = demandes;
        if (requestNumberSearch.trim()) {
          filteredRequests = demandes.filter(
            (r: any) => r.num_demande.toLowerCase().includes(requestNumberSearch.toLowerCase())
          );
        }

        // Transform API data to frontend format
        const transformedRequests: DocumentRequest[] = filteredRequests.map((req: any) => ({
          id: req.id.toString(),
          requestNumber: req.num_demande,
          studentId: req.etudiant.apogee,
          documentType: req.type_document as any,
          status: req.status as any,
          createdAt: new Date(req.date_demande.split('/').reverse().join('-')),
          updatedAt: new Date(), // API doesn't provide this
          details: req.details,
          documentUrl: req.status === 'validee' ? '#' : undefined,
        }));

        setRequests(transformedRequests);
        toast.success(`${transformedRequests.length} demande(s) trouvée(s)`);
      } else {
        toast.error(response.data.message || "Erreur lors de la recherche");
      }
    } catch (error: any) {
      if (error.response?.status === 404) {
        toast.error("Aucune demande trouvée avec ce numéro.");
      } else {
        toast.error("Erreur lors de la recherche de vos demandes");
      }
    } finally {
      setIsLoading(false);
    }
  };

  const getStatusTimeline = (status: string) => {
    const steps = [
      { id: "submitted", label: "Soumise", completed: true },
      { id: "processing", label: "En traitement", completed: status !== "en_attente" },
      { id: "completed", label: "Terminée", completed: status === "validee" || status === "refusee" },
    ];
    return steps;
  };

  return (
    <Layout>
      <div className="min-h-[calc(100vh-4rem)] bg-gradient-to-b from-background to-accent/20 py-8 lg:py-12">
        <div className="container max-w-4xl">
          {/* Header */}
          <div className="text-center mb-8">
            <h1 className="text-3xl font-bold text-foreground mb-2">Suivi de demande</h1>
            <p className="text-muted-foreground">
              Consultez l'état de vos demandes en cours
            </p>
          </div>

          {/* Request Number Search */}
          <div className="mb-8 p-4 bg-muted/30 rounded-xl">
            <div className="space-y-2">
              <Label htmlFor="requestNumberSearch" className="flex items-center gap-2">
                <Hash className="h-4 w-4" />
                Numéro de demande
              </Label>
              <Input
                id="requestNumberSearch"
                value={requestNumberSearch}
                onChange={(e) => setRequestNumberSearch(e.target.value)}
                placeholder="Ex: DMD-2024-0001"
              />
              <p className="text-xs text-muted-foreground">
                Entrez un numéro de demande pour rechercher
              </p>
            </div>

            <div className="mt-4">
              <Button onClick={searchByRequestNumber} disabled={isLoading} className="gap-2">
                {isLoading ? (
                  "Recherche..."
                ) : (
                  <>
                    <Search className="h-4 w-4" />
                    Rechercher
                  </>
                )}
              </Button>
            </div>

            {/* Demo hint */}
            <div className="mt-4 p-3 bg-muted/50 rounded-lg">
              <p className="text-xs text-muted-foreground">
                <strong>Démo:</strong> Entrez un numéro de demande (ex: DMD-2024-0001)
              </p>
            </div>
          </div>

          {/* Results */}
          {requests && requests.length > 0 && (
            <div className="space-y-6 animate-fade-in">
              <h2 className="text-xl font-semibold text-foreground">
                Vos demandes ({requests.length})
              </h2>

              {requests.map((request) => (
                <div
                  key={request.id}
                  className="rounded-2xl border border-border bg-card p-6 shadow-sm hover:shadow-md transition-shadow"
                >
                  {/* Header */}
                  <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div className="flex items-center gap-3">
                      <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <FileText className="h-6 w-6" />
                      </div>
                      <div>
                        <h3 className="font-semibold text-foreground">
                          {documentTypeLabels[request.documentType]}
                        </h3>
                        <div className="flex items-center gap-2 text-sm text-muted-foreground">
                          <Hash className="h-3.5 w-3.5" />
                          {request.requestNumber}
                        </div>
                      </div>
                    </div>
                    <StatusBadge status={request.status} />
                  </div>

                  {/* Timeline */}
                  <div className="mb-6">
                    <div className="flex items-center justify-between">
                      {getStatusTimeline(request.status).map((step, index) => (
                        <div key={step.id} className="flex items-center">
                          <div className="flex flex-col items-center">
                            <div
                              className={`flex h-8 w-8 items-center justify-center rounded-full ${step.completed
                                ? "bg-success text-success-foreground"
                                : "bg-muted text-muted-foreground"
                                }`}
                            >
                              {step.completed ? (
                                <CheckCircle2 className="h-4 w-4" />
                              ) : (
                                <Clock className="h-4 w-4" />
                              )}
                            </div>
                            <span className="mt-2 text-xs font-medium text-muted-foreground">
                              {step.label}
                            </span>
                          </div>
                          {index < 2 && (
                            <div
                              className={`h-0.5 w-16 sm:w-24 lg:w-32 mx-2 ${step.completed ? "bg-success" : "bg-muted"
                                }`}
                            />
                          )}
                        </div>
                      ))}
                    </div>
                  </div>

                  {/* Details */}
                  <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-6">
                    <div className="flex items-center gap-2 text-sm">
                      <Calendar className="h-4 w-4 text-muted-foreground" />
                      <span className="text-muted-foreground">Créée le:</span>
                      <span className="font-medium">
                        {request.createdAt.toLocaleDateString("fr-FR")}
                      </span>
                    </div>
                    <div className="flex items-center gap-2 text-sm">
                      <Clock className="h-4 w-4 text-muted-foreground" />
                      <span className="text-muted-foreground">Mise à jour:</span>
                      <span className="font-medium">
                        {request.updatedAt.toLocaleDateString("fr-FR")}
                      </span>
                    </div>
                  </div>

                  {/* Request Details */}
                  {request.details && Object.keys(request.details).length > 0 && (
                    <div className="rounded-lg bg-muted/50 p-4 mb-6">
                      <h4 className="text-sm font-semibold text-foreground mb-3">
                        Détails de la demande
                      </h4>
                      <div className="space-y-4">
                        {Object.entries(request.details).map(([key, value]) => {
                          // Special rendering for 'resultats' (Transcript notes)
                          if (key === 'resultats' && Array.isArray(value)) {
                            return (
                              <div key={key} className="col-span-full mt-2">
                                <h5 className="text-sm font-medium mb-2 text-primary">Résultats académiques</h5>
                                <div className="border rounded-md overflow-hidden bg-background">
                                  <table className="w-full text-sm text-left">
                                    <thead className="bg-muted text-muted-foreground">
                                      <tr>
                                        <th className="p-2 font-medium">Module</th>
                                        <th className="p-2 font-medium text-center">Note</th>
                                        <th className="p-2 font-medium text-right">Résultat</th>
                                      </tr>
                                    </thead>
                                    <tbody className="divide-y">
                                      {value.map((note: any, idx: number) => (
                                        <tr key={idx} className="hover:bg-accent/5">
                                          <td className="p-2">{note.module}</td>
                                          <td className="p-2 text-center font-medium">
                                            <span className={cn(
                                              Number(note.note) >= 10 ? "text-green-600" : "text-destructive"
                                            )}>
                                              {note.note}/20
                                            </span>
                                          </td>
                                          <td className="p-2 text-right">
                                            <span className={cn(
                                              "inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium",
                                              note.resultat === 'Validé'
                                                ? "bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400"
                                                : "bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400"
                                            )}>
                                              {note.resultat}
                                            </span>
                                          </td>
                                        </tr>
                                      ))}
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            );
                          }

                          // Standard key-value rendering
                          if (typeof value !== 'object' || value === null) {
                            return (
                              <div key={key} className="flex justify-between text-sm border-b border-border/50 pb-2 last:border-0 last:pb-0">
                                <span className="text-muted-foreground capitalize">
                                  {key.replace(/_/g, " ").trim()} :
                                </span>
                                <span className="font-medium text-foreground">{value}</span>
                              </div>
                            );
                          }

                          return null;
                        })}
                      </div>
                    </div>
                  )}

                  {/* Actions */}
                  <div className="flex flex-wrap gap-3">
                    {request.status === "validee" && request.documentType === 'releve_notes' && (
                      <Button
                        variant="default"
                        size="sm"
                        onClick={() => setSelectedTranscript(request)}
                      >
                        <Download className="mr-2 h-4 w-4" />
                        Voir / Télécharger le Relevé
                      </Button>
                    )}
                    {request.status === "validee" && request.documentType !== 'releve_notes' && request.documentUrl && (
                      <Button variant="default" size="sm">
                        <Download className="mr-2 h-4 w-4" />
                        Télécharger le document
                      </Button>
                    )}
                    <Link to={`/reclamation?demande=${request.requestNumber}`}>
                      <Button variant="outline" size="sm">
                        <MessageSquare className="mr-2 h-4 w-4" />
                        Faire une réclamation
                      </Button>
                    </Link>
                  </div>
                </div>
              ))}
            </div>
          )}

          {selectedTranscript && (
            <ReleveNotesTemplate
              data={{
                etudiant: {
                  nom: selectedTranscript.etudiant?.nom || "Nom",
                  prenom: selectedTranscript.etudiant?.prenom || "Prenom",
                  apogee: selectedTranscript.studentId,
                  cin: selectedTranscript.etudiant?.cin || "",
                  date_naissance: selectedTranscript.etudiant?.date_naissance,
                  lieu_naissance: selectedTranscript.etudiant?.lieu_naissance
                },
                details: selectedTranscript.details
              }}
              onClose={() => setSelectedTranscript(null)}
            />
          )}




          {/* Empty State */}
          {!requests && (
            <div className="text-center py-12">
              <div className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-muted">
                <Search className="h-8 w-8 text-muted-foreground" />
              </div>
              <h3 className="text-lg font-semibold text-foreground mb-2">
                Recherchez votre demande
              </h3>
              <p className="text-muted-foreground max-w-md mx-auto">
                Entrez le numéro de votre demande pour consulter
                l'état de votre demande administrative.
              </p>
            </div>
          )}
        </div>
      </div>
    </Layout>
  );
}
