export type DocumentType = 
  | "attestation_scolaire"
  | "attestation_reussite"
  | "releve_notes"
  | "convention_stage";

export type RequestStatus = 
  | "en_attente"
  | "en_cours"
  | "validee"
  | "rejetee";

export type ReclamationStatus = 
  | "non_traitee"
  | "en_cours"
  | "traitee";

export type ReclamationType = 
  | "retard"
  | "refus_injustifie"
  | "document_incorrect"
  | "probleme_technique";

export interface Student {
  id: string;
  email: string;
  apogee: string;
  cin: string;
  nom: string;
  prenom: string;
  filiere: string;
  niveau: string;
}

export interface DocumentRequest {
  id: string;
  requestNumber: string;
  studentId: string;
  student?: Student;
  documentType: DocumentType;
  status: RequestStatus;
  createdAt: Date;
  updatedAt: Date;
  details: Record<string, string>;
  refusalReason?: string;
  documentUrl?: string;
}

export interface Reclamation {
  id: string;
  requestId: string;
  studentId: string;
  studentName?: string;
  studentApogee?: string;
  type: ReclamationType;
  description: string;
  status: ReclamationStatus;
  attachmentUrl?: string;
  response?: string;
  createdAt: Date | string;
  updatedAt: Date | string;
}

export const documentTypeLabels: Record<DocumentType, string> = {
  attestation_scolaire: "Attestation de scolarité",
  attestation_reussite: "Attestation de réussite",
  releve_notes: "Relevé de notes",
  convention_stage: "Convention de stage",
};

export const statusLabels: Record<RequestStatus, string> = {
  en_attente: "En attente",
  en_cours: "En cours",
  validee: "Validée",
  rejetee: "Refusée",
};

export const reclamationTypeLabels: Record<ReclamationType, string> = {
  retard: "Retard",
  refus_injustifie: "Refus injustifié",
  document_incorrect: "Document incorrect",
  probleme_technique: "Problème technique",
};

export const reclamationStatusLabels: Record<ReclamationStatus, string> = {
  non_traitee: "Non traitée",
  en_cours: "En cours",
  traitee: "Traitée",
};
