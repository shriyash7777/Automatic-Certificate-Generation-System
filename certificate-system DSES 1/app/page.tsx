"use client"

import { useState } from "react"
import { CertificateDesigner } from "@/components/certificate-designer"
import { CertificatePreview } from "@/components/certificate-preview"
import { Button } from "@/components/ui/button"
import { Card } from "@/components/ui/card"
import { Tabs, TabsList, TabsTrigger } from "@/components/ui/tabs"

export interface CertificateConfig {
  // Company Information
  companyName: string
  companySubtitle: string
  companyId: string
  contactInfo: {
    email: string
    website: string
    phone: string
    address: string
  }

  // Certificate Content
  certificateTitle: string
  studentName: string
  courseHours: string
  courseName: string
  startDate: string
  endDate: string
  instructorName: string
  instructorTitle: string

  // Design Settings
  colors: {
    primary: string
    secondary: string
    accent: string
    text: string
    border: string
  }
  fonts: {
    heading: string
    body: string
  }
  layout: {
    borderStyle: "geometric" | "classic" | "modern"
    showLogos: boolean
    qrPosition: "left" | "right" | "center"
  }
}

const defaultConfig: CertificateConfig = {
  companyName: "DNYANDA SUSTAINABLE ENGINEERING SOLUTIONS PRIVATE LIMITED",
  companySubtitle: "A Sustainable Development Venture",
  companyId: "UDYAM-MH-26-0420933",
  contactInfo: {
    email: "dses.contact@gmail.com",
    website: "www.dnyandases.com",
    phone: "+91 7028975161",
    address: "Ravet, Pune (MS) India - 412101",
  },
  certificateTitle: "CERTIFICATE OF MERIT",
  studentName: "Student Name",
  courseHours: "42",
  courseName: "Robotics",
  startDate: "2025-02-03",
  endDate: "2025-02-08",
  instructorName: "Dr. Vikas Shinde",
  instructorTitle: "Director",
  colors: {
    primary: "#8B4513",
    secondary: "#4CAF50",
    accent: "#2196F3",
    text: "#333333",
    border: "#666666",
  },
  fonts: {
    heading: "font-serif",
    body: "font-sans",
  },
  layout: {
    borderStyle: "geometric",
    showLogos: true,
    qrPosition: "left",
  },
}

export default function Home() {
  const [config, setConfig] = useState<CertificateConfig>(defaultConfig)
  const [activeTab, setActiveTab] = useState("design")

  const handleConfigChange = (updates: Partial<CertificateConfig>) => {
    setConfig((prev) => ({ ...prev, ...updates }))
  }

  const handleDownload = () => {
    window.print()
  }

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="container mx-auto px-4 py-4">
          <h1 className="text-2xl font-bold">Certificate Designer</h1>
          <p className="text-muted-foreground">Customize and generate professional certificates</p>
        </div>
      </header>

      <div className="container mx-auto px-4 py-6">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
          {/* Designer Panel */}
          <Card className="p-6">
            <Tabs value={activeTab} onValueChange={setActiveTab}>
              <TabsList className="grid w-full grid-cols-3">
                <TabsTrigger value="content">Content</TabsTrigger>
                <TabsTrigger value="design">Design</TabsTrigger>
                <TabsTrigger value="layout">Layout</TabsTrigger>
              </TabsList>

              <div className="mt-6">
                <CertificateDesigner config={config} onConfigChange={handleConfigChange} activeTab={activeTab} />
              </div>
            </Tabs>
          </Card>

          {/* Preview Panel */}
          <Card className="p-6">
            <div className="flex items-center justify-between mb-4">
              <h2 className="text-lg font-semibold">Live Preview</h2>
              <Button onClick={handleDownload} className="gap-2">
                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                  />
                </svg>
                Download PDF
              </Button>
            </div>

            <div className="border rounded-lg overflow-hidden">
              <CertificatePreview config={config} />
            </div>
          </Card>
        </div>
      </div>
    </div>
  )
}
